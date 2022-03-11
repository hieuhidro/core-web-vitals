<?php

/**
 * Created by Hidro Le.
 * Email: hieu@solutiontutorials.com
 * Date: 19/08/2021
 * Time: 21:34
 */

namespace Hidro\CoreWebVitals\Model;

use Hidro\CoreWebVitals\Service\Asset\CriticalCssInterface;
use Hidro\CoreWebVitals\Service\Asset\PreloadInterface;
use Hidro\CoreWebVitals\Service\Asset\DeferCSSInterface;
use \Magento\Framework\View\Page\Config as PageConfig;
use Zend\Http\Headers;

class AssetService
{
    /**
     * @var CriticalCssInterface
     */
    protected $criticalCss;

    /**
     * @var PreloadInterface
     */
    protected $preloadAsset;

    /**
     * @var DeferCSSInterface
     */
    protected $deferCSS;

    public function __construct(
        CriticalCssInterface $criticalCss,
        PreloadInterface     $preloadAsset,
        DeferCSSInterface    $deferCSS
    ) {
        $this->criticalCss = $criticalCss;
        $this->preloadAsset = $preloadAsset;
        $this->deferCSS = $deferCSS;
    }

    /**
     * @param            $resultGroups
     * @param PageConfig $pageConfig
     * @return string[]
     */
    public function modifyResultGroups($resultGroups, $pageConfig)
    {
        $sortedResultGroups['woff2'] = '';
        $sortedResultGroups['woff'] = '';
        $sortedResultGroups['ttf'] = '';
        $sortedResultGroups['eot'] = '';
        $sortedResultGroups['css'] = '';

        $criticalFront = '<style data-type="criticalCss">' .
            $this->criticalCss->getFontCritical() . '</style>' . PHP_EOL;

        $criticalCss = '<style data-type="criticalCss">' .
            $this->criticalCss->getDefaultCritical() . '</style>' . PHP_EOL;

        $criticalBodyClass = '<style data-type="criticalCss">' .
            $this->criticalCss->getCriticalContent($pageConfig->getElementAttribute('body', 'class')) . '</style>' . PHP_EOL;

        $sortedResultGroups['css'] .= $criticalFront;
        $sortedResultGroups['css'] .= $criticalCss;
        $sortedResultGroups['css'] .= $criticalBodyClass;

        foreach ($resultGroups as $key => $value) {
            if (isset($sortedResultGroups[$key])) {
                $sortedResultGroups[$key] .= $value;
            } else {
                $sortedResultGroups[$key] = '';
            }
        }
        return $sortedResultGroups;
    }

    public function pushPreloadAsset($file, $url, $type)
    {
        if (in_array($type, $this->preloadAsset->getSupportTypes()) !== false) {
            if ($this->preloadAsset->isPreload($file)) {
                $this->preloadAsset->registerFile($url, $type);
            }
        }
    }

    /**
     * @param Headers $headers
     */
    public function pushPreloadHeader($headers)
    {
        if (!empty($this->preloadAsset->getPreloadFiles())) {
            $this->preloadAsset->registerPlugin($headers);
            $this->preloadAsset->appendPreload($headers);
        }
    }

    public function pushDeferByScript($file, $url, $attribute)
    {
        if ($this->deferCSS->isDefer($file)) {
            $this->deferCSS->registerFile($url, $attribute);
            return true;
        }
        return false;
    }

    public function renderDeferCSSByScript()
    {
        $result = $this->deferCSS->renderDeferFiles();
        $this->deferCSS->cleanFiles();
        return $result;
    }
}
