<?php

/**
 * Created by Hidro Le.
 * Email: hieu@solutiontutorials.com
 * Date: 21/08/2021
 * Time: 00:12
 */

namespace Hidro\CoreWebVitals\Model\Html\Modifier;

use Hidro\CoreWebVitals\Service\Html\OutputModifierInterface;

class RocketLoader extends AbstractModifier
{
    /**
     * @var string
     */
    private $rocketLoaderURL;

    /**
     * @var string
     */
    private $rocketLoaderTagHtml;

    public function isEnabled()
    {
        return $this->helper->isRocketLoader();
    }

    /**
     * @return string
     */
    protected function getRocketLoaderTag()
    {
        if (!$this->rocketLoaderTagHtml) {
            $scriptIdentify = $this->getRocketLoaderIdentify();
            //<script src="" data-cf-settings="-|49" defer=""></script>
            $this->rocketLoaderTagHtml = sprintf(
                '<script src="%s" data-cf-settings="%s-|49"></script>',
                static::getRocketLoaderScript(),
                $scriptIdentify
            );
        }
        return $this->rocketLoaderTagHtml;
    }

    /**
     * @param $content
     * @param $pattern
     * @return array|string|string[]|null
     */
    protected function formatUnTypeScript($content, $pattern)
    {
        $_content = preg_replace_callback(
            '/<script\s*?src\s*=\s*["\'].*?js[\w\?&=%-]*["\']?\s*?>/is',
            function ($script) use ($pattern) {
                return str_replace('<script ', sprintf('<script type="%s" ', $pattern), $script[0]);
            },
            $content
        );

        //If preg_replace_callback has an error. revert the html
        if (null !== $_content) {
            $content = $_content;
        }
        return $content;
    }

    /**
     * @param $content
     * @param $pattern
     * @return array|string|string[]|null
     */
    protected function formatEmptyTagScript($content, $pattern)
    {
        $_content = preg_replace_callback(
            '/^(?!("\'))<script\s?.*?>/is',
            function ($script) use ($pattern) {
                return str_replace('<script ', sprintf('<script type="%s" ', $pattern), $script[0]);
            },
            $content
        );
        //Revert html to default when preg_replace_callback has had error.
        if(null !== $_content){
            $content = $_content;
        }
        return $content;
    }

    /**
     * @param string $html
     * @return string
     */
    protected function modifyJavascript(string $html): ?string
    {
        $ignoreParts = $this->excludeScriptTags();
        $scriptIdentify = $this->getRocketLoaderIdentify();

        $callback = function (array $script) use ($ignoreParts, $scriptIdentify): ?string {
            $content = $script[0];

            if ($this->shouldIgnoreScript($content, $ignoreParts)) {
                return $content;
            }

            $_content = $this->updateScriptTagType($content, $scriptIdentify);
            if (null !== $_content) {
                $content = $_content;
            }
            return $this->formatScriptTag($content, $scriptIdentify);
        };

        $_html = preg_replace_callback('/<script[^>]*>(?>.*?<\/script>)/is', $callback, $html);

        if (null !== $_html) {
            $rocketLoaderTag = sprintf(
                '<script src="%s" data-cf-settings="%s-|49" defer=""></script>',
                static::getRocketLoaderScript(),
                $scriptIdentify
            );
            $html = str_replace('</body', $rocketLoaderTag . '</body', $_html);
        }

        return $html;
    }

    private function shouldIgnoreScript(string $content, array $ignoreParts): bool
    {
        if (strpos($content, 'BASE_URL') !== false) {
            return true;
        }

        foreach ($ignoreParts as $ignorePart) {
            if (strpos($content, $ignorePart) !== false) {
                return true;
            }
        }

        return false;
    }

    private function updateScriptTagType(string $content, string $scriptIdentify): ?string
    {
        $pattern = $scriptIdentify . '-text/javascript';
        $callback = function (array $scripts) use ($pattern): ?string {
            $script = $scripts[0];
            $script = str_replace('text/javascript', $pattern, $script);
            $script = str_replace('application/javascript', $pattern, $script);
            return $script;
        };

        return preg_replace_callback('/^<script[^>]+type=["\']text\/javascript["\']|<script[^>]+type=["\']application\/javascript["\']/is', $callback, $content);
    }

    private function formatScriptTag(string $content, string $scriptIdentify): ?string
    {
        $pattern = $scriptIdentify . '-text/javascript';
        $content = preg_replace('/^<script?[\s|\w]>/is', sprintf('<script type="%s">', $pattern), $content);
        if (strpos($content, 'type=') === false) {
            $content = $this->formatUnTypeScript($content, $pattern);
        }
        if (strpos($content, 'type=') === false) {
            $content = $this->formatEmptyTagScript($content, $pattern);
        }

        return $content;
    }

    /**
     * @inheritDoc
     */
    public function modify($html)
    {
        return $this->modifyJavascript($html);
    }

    /**
     * @return string[]
     */
    public function excludeScriptTags()
    {
        return [
            'data-cfasync="false"'
        ];
    }

    /**
     * @return string
     */
    public function getRocketLoaderIdentify()
    {
        return '79505dfc4200030404c885ec';
    }

    /**
     * @return string
     */
    public function getRocketLoaderScript()
    {
        if (!$this->rocketLoaderURL) {
            $this->rocketLoaderURL = 'https://ajax.cloudflare.com/cdn-cgi/scripts/' .
                strtotime('first day of ' . date('F Y')) .
                '/cloudflare-static/rocket-loader.min.js';
        }
        return $this->rocketLoaderURL;
    }
}
