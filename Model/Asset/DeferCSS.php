<?php
/**
 * Created by Hidro Le.
 * Email: hieu@solutiontutorials.com
 * Date: 05/10/2021
 * Time: 17:15
 */

namespace Hidro\CoreWebVitals\Model\Asset;

use Hidro\CoreWebVitals\Model\Html\Context as CoreWebVitalsContext;
use Hidro\CoreWebVitals\Service\Asset\DeferCSSInterface;

class DeferCSS implements DeferCSSInterface
{
    /**
     * @var \Hidro\CoreWebVitals\Helper\Data
     */
    protected $helper;

    /**
     * @var array
     */
    protected $registeredFiles = [];

    /**
     * @var array
     */
    private static $deferFiles = [];

    /**
     * @param array $preLoads
     */
    public function __construct(
        CoreWebVitalsContext $context
    ) {
        $this->helper = $context->getHelper();
        $this->registeredFiles = $this->helper->getDeferCSSFiles();
    }

    /**
     * @param $file
     * @return bool
     */
    public function isDefer($file)
    {
        return (!!$this->helper->getDeferCSSMode()) && in_array($file, $this->registeredFiles);
    }

    /**
     * @return $this
     */
    public function cleanFiles()
    {
        self::$deferFiles = [];
        return $this;
    }

    /**
     * @param $url
     * @param $attribute
     * @return array|\Hidro\CoreWebVitals\Service\Asset\PreloadInterface
     */
    public function registerFile($url, $attribute)
    {
        if (!isset(self::$deferFiles[$url])) {
            self::$deferFiles[$url] = $attribute;
        }
        return self::$deferFiles;
    }

    /**
     *
     * @return string
     */
    public function renderDeferFiles()
    {
        $rel = 'javascript';
        //adding onload to stylesheet.
        $attributes = [];
        switch ($this->helper->getDeferCSSMode()) {
            case self::DEFAULT_BROWSER:
                $rel = 'preload';
                $attributes = [
                    'onload="this.rel=\'stylesheet\'"'
                ];
                break;
            case self::JAVASCRIPT_PRELOAD:
                $rel = 'cwv_preload';
                break;
        }
        $template = '<link rel="%s" as="style" type="text/css" %s href="%s" %s />' . "\n";
        $output = '';
        foreach (self::$deferFiles as $url => $attribute) {
            $output .= sprintf($template, $rel, $attribute, $url, implode(' ', $attributes));
        }
        return $output;
    }
}
