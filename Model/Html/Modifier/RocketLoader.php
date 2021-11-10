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
     * @param string $html
     * @return string
     */
    protected function modifyJavascript($html)
    {
        $ignoreParts = $this->excludeScriptTags();
        $scriptIdentify = $this->getRocketLoaderIdentify();
        $_html = preg_replace_callback(
            '/<script[^>]*>(?>.*?<\/script>)/is',
            function ($script) use ($ignoreParts, $scriptIdentify) {
                $content = $script[0];
                $ignore = false;
                if (strpos($script[0], 'BASE_URL') !== false) {
                    return $script[0];
                }
                if (!empty($ignoreParts)) {
                    foreach ($ignoreParts as $ignorePart) {
                        if (strpos($content, $ignorePart) !== false) {
                            $ignore = true;
                            break;
                        }
                    }
                }
                if (!$ignore) {
                    $pattern = $scriptIdentify . '-text/javascript';
                    $_content = preg_replace_callback(
                        '/^(?!("\'))<script[^>]+type=["\']text\/javascript["\']|<script[^>]+type=["\']application\/javascript["\']/is', //@phpcs ignore
                        function ($scripts) use ($pattern) {
                            $script = $scripts[0];
                            $script = str_replace('text/javascript', $pattern, $script);
                            $script = str_replace('application/javascript', $pattern, $script);
                            return $script;
                        },
                        $content
                    );
                    //If preg_replace_callback has an error. revert the html
                    if (null !== $_content) {
                        $content = $_content;
                    }
                    $content = preg_replace('/<script?[\s|\w]>/is', sprintf('<script type="%s">', $pattern), $content);
                    if (strpos($content, 'type=') === false) {
                        $content = $this->formatUnTypeScript($content, $pattern);
                    }
                }
                return $content;
            },
            $html
        );
        //If preg_replace_callback has an error. revert the html
        if (null !== $_html) {
            $html = $_html;
        }
        //<script src="" data-cf-settings="-|49" defer=""></script>
        $rocketLoaderTagHtml = sprintf(
            '<script src="%s" data-cf-settings="%s-|49" defer=""></script>',
            static::getRocketLoaderScript(),
            $scriptIdentify
        );
        return str_replace('</body', $rocketLoaderTagHtml . '</body', $html);
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
