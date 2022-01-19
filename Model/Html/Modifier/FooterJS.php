<?php

/**
 * Created by Hidro Le.
 * Email: hieu@solutiontutorials.com
 * Date: 22/08/2021
 * Time: 09:56
 */

namespace Hidro\CoreWebVitals\Model\Html\Modifier;

use Hidro\CoreWebVitals\Model\Html\Context;
use Hidro\CoreWebVitals\Service\Asset\MinificationInterface;

class FooterJS extends AbstractModifier
{

    /**
     * @var MinificationInterface
     */
    protected $minification;

    /**
     * @param Context               $context
     * @param MinificationInterface $minification
     */
    public function __construct(
        Context               $context,
        MinificationInterface $minification
    ) {
        parent::__construct($context);
        $this->minification = $minification;
    }

    public function isEnabled()
    {
        return $this->helper->isMoveJsToFooter();
    }

    /**
     * @param string $html
     * @return string
     */
    public function modify($html)
    {

        $script = [];
        $ignoreParts = $this->excludeScriptTags();
        $pattern = '#<script[^>]*+(?<!text/x-magento-template.)>(.*?)</script>#is';
        $_html = preg_replace_callback(
            $pattern,
            function ($matchPart) use (&$script, $ignoreParts) {
                $ignore = false;
                if (strpos($matchPart[1], 'BASE_URL') !== false) {
                    return $matchPart[0];
                }
                if (!empty($ignoreParts)) {
                    foreach ($ignoreParts as $ignorePart) {
                        if (strpos($matchPart[0], $ignorePart) !== false) {
                            $ignore = true;
                            break;
                        }
                    }
                }
                if (!$ignore) {
                    if ($this->helper->isMinifyInlineJs()) {
                        preg_match('#<script[^>]*+(?<!text/x-magento-template.)>#is', $matchPart[0], $began);
                        if ($began) {
                            $began = $began[0];
                            $_script = $this->minification->minify($matchPart[1]);
                            $script[] = $began . $_script . '</script>';
                            return '';
                        }
                    }
                    $script[] = $matchPart[0];
                    return '';
                }
                return $matchPart[0];
            },
            $html
        );
        if (null !== $_html) {
            $html = $_html;
            $html = str_replace('</body', implode("\n", $script) . "\n</body", $html);
        }
        return $html;
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
}
