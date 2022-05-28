<?php
/**
 * Created by Hidro Le.
 * Email: hieu@solutiontutorials.com
 * Date: 19/08/2021
 * Time: 21:17
 */

namespace Hidro\CoreWebVitals\Block\View\Page\Config;

use Hidro\CoreWebVitals\Model\AssetService;
use Magento\Framework\View\Asset\ConfigInterface;
use Magento\Framework\View\Asset\GroupedCollection;
use Magento\Framework\View\Page\Config;
use Magento\Framework\View\Page\Config\Metadata\MsApplicationTileImage;
use Hidro\CoreWebVitals\Service\Asset\FooterCSSInterface;

class Renderer extends \Magento\Framework\View\Page\Config\Renderer
{
    /**
     * @var AssetService
     */
    protected $assetService;

    /**
     * @var FooterCSSInterface
     */
    protected $footerCSS;

    /**
     * @var ConfigInterface
     */
    protected $config;

    /**
     * @param Config                                     $pageConfig
     * @param \Magento\Framework\View\Asset\MergeService $assetMergeService
     * @param \Magento\Framework\UrlInterface            $urlBuilder
     * @param \Magento\Framework\Escaper                 $escaper
     * @param \Magento\Framework\Stdlib\StringUtils      $string
     * @param \Psr\Log\LoggerInterface                   $logger
     * @param AssetService                               $assetService
     * @param FooterCSSInterface                         $footerCSS
     * @param ConfigInterface                            $config
     */
    public function __construct(
        Config $pageConfig,
        \Magento\Framework\View\Asset\MergeService $assetMergeService,
        \Magento\Framework\UrlInterface $urlBuilder,
        \Magento\Framework\Escaper $escaper,
        \Magento\Framework\Stdlib\StringUtils $string,
        \Psr\Log\LoggerInterface $logger,
        AssetService $assetService,
        FooterCSSInterface $footerCSS,
        ConfigInterface $config
    ) {
        $this->assetService = $assetService;
        $this->footerCSS = $footerCSS;
        $this->config = $config;
        parent::__construct($pageConfig, $assetMergeService, $urlBuilder, $escaper, $string, $logger);
    }

    /**
     * @param array $resultGroups
     * @return string
     */
    public function renderAssets($resultGroups = [])
    {
        $sortedResultGroups = $this->assetService->modifyResultGroups($resultGroups, $this->pageConfig);
        return parent::renderAssets($sortedResultGroups);
    }

    /**
     * @param \Magento\Framework\View\Asset\PropertyGroup $group
     * @return string
     */
    protected function renderAssetHtml(\Magento\Framework\View\Asset\PropertyGroup $group)
    {
        //We have to disable merge js, css
        $contentType = $group->getProperty(GroupedCollection::PROPERTY_CONTENT_TYPE);
        $isCss = $contentType == 'css';
        $isJs = $contentType == 'js';
        $isCssMergeEnabled = $this->config->isMergeCssFiles();
        $isJsMergeEnabled = $this->config->isMergeJsFiles();
        if (($isCss && !$isCssMergeEnabled) || ($isJs && !$isJsMergeEnabled)) {
            $attributes = $this->getGroupAttributes($group);
            $assets = $group->getAll();
            foreach ($assets as $key => $asset) {
                $type = $this->getAssetContentType($asset);
                $this->assetService->pushPreloadAsset($key, $asset->getUrl(), $type);
                if ($this->assetService->pushDeferByScript($key, $asset->getUrl(), $attributes)) {
                    $group->remove($key);
                } elseif ($this->footerCSS->canMove($key)) {
                    $this->footerCSS->registerFile($key, $asset);
                    $group->remove($key);
                }
            }
        }
        $result = parent::renderAssetHtml($group);
        return $result . $this->assetService->renderDeferCSSByScript();
    }
}
