<?php

/**
 * Created by Hidro Le.
 * Email: hieu@solutiontutorials.com
 * Date: 22/08/2021
 * Time: 09:56
 */

namespace Hidro\CoreWebVitals\Model\Html\Modifier;

use Hidro\CoreWebVitals\Model\Html\Context;
use Hidro\CoreWebVitals\Service\Asset\DeferCSSInterface;
use Hidro\CoreWebVitals\Service\Asset\FooterCSSInterface;
use Hidro\CoreWebVitals\Service\Asset\PreloadInterface;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\View\Asset\AssetInterface;

class FooterCSS extends AbstractModifier implements FooterCSSInterface
{
    private static $footerFiles  = [];
    protected $state;
    protected $allowedFiles = null;

    public function __construct(
        Context                      $context,
        \Magento\Framework\App\State $state
    ) {
        $this->state = $state;
        parent::__construct($context);
    }

    public function isEnabled()
    {
        return $this->helper->isMoveCSSToFooter();
    }

    /**
     * return file id
     *
     * @return string[]
     */
    protected function getAllowedFiles()
    {
        if (null === $this->allowedFiles) {
            $files = [
                "css/styles-custom.css",
                "mage/gallery/gallery.css",
                "mage/calendar.css",
            ];
            $this->allowedFiles = array_merge($files, $this->helper->listOfMoveFooterCSS());
        }
        return $this->allowedFiles;
    }

    /**
     * @inheritDoc
     */
    public function registerFile($fileId, $asset)
    {
        if (!isset(self::$footerFiles[$fileId])) {
            self::$footerFiles[$fileId] = $asset;
        }
        return $this;
    }

    /**
     * @return string[]|void
     */
    public function getFooterAssetFiles()
    {
        return self::$footerFiles;
    }

    /**
     * Merge footer css to 1 file.
     *
     * @param $assets
     * @return \Magento\Framework\View\Asset\Merged|mixed
     */
    protected function mergeAssets($assets)
    {
        $mergeStrategyClass = \Magento\Framework\View\Asset\MergeStrategy\FileExists::class;
        if ($this->state->getMode() === \Magento\Framework\App\State::MODE_DEVELOPER) {
            $mergeStrategyClass = \Magento\Framework\View\Asset\MergeStrategy\Checksum::class;
        }
        $mergeStrategy = ObjectManager::getInstance()->get($mergeStrategyClass);
        $assets = ObjectManager::getInstance()->create(
            \Magento\Framework\View\Asset\Merged::class,
            ['assets' => $assets, 'mergeStrategy' => $mergeStrategy]
        );
        return $assets;
    }

    /**
     * @param string $html
     * @return string
     */
    public function modify($html)
    {
        $assets = $this->getFooterAssetFiles();
        if (count($assets)) {
            $assets = $this->mergeAssets($assets);
            $rel = 'javascript';
            switch ($this->helper->getDeferCSSMode()) {
                case DeferCSSInterface::DEFAULT_BROWSER:
                    $rel = 'preload';
                    break;
                case DeferCSSInterface::JAVASCRIPT_PRELOAD:
                    $rel = 'cwv_preload';
                    break;
            }
            $template = '<link rel="%s" as="style" type="text/css" media="all" href="%s" />' . "\n";
            $moveStyles = '';
            foreach ($assets as $asset) {
                $moveStyles .= sprintf($template, $rel, $asset->getUrl());
            }
            $html = str_replace('</body', $moveStyles . '</body', $html);
        }
        return $html;
    }

    /**
     * @param $fileId
     * @return bool|void
     */
    public function canMove($fileId)
    {
        $fileIds = $this->getAllowedFiles();
        return in_array($fileId, $fileIds);
    }
}
