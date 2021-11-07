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
    protected $helper;

    protected $registeredFiles = [];

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

    public function isDefer($file)
    {
        return in_array($file, $this->registeredFiles);
    }


    public function cleanFiles(){
        self::$deferFiles = [];
    }

    public function registerFile($url, $attribute)
    {
        if (!isset(self::$deferFiles[$url])) {
            self::$deferFiles[$url] = $attribute;
        }
        return self::$deferFiles;
    }

    public function renderDeferFiles()
    {
        $template = '<link rel="cwv_preload" as="style" type="text/css" %s href="%s"  />' . "\n";
        $output = '';
        foreach (self::$deferFiles as $url => $attribute) {
            $output .= sprintf($template, $attribute, $url);
        }
        return $output;
    }
}
