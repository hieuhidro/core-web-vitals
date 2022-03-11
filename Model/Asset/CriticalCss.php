<?php

/**
 * Created by Hidro Le.
 * Email: hieu@solutiontutorials.com
 * Date: 19/08/2021
 * Time: 23:43
 */

namespace Hidro\CoreWebVitals\Model\Asset;

use Hidro\CoreWebVitals\Service\Asset\CriticalCssInterface;
use Hidro\CoreWebVitals\Service\Asset\MinificationInterface;
use Magento\PageCache\Model\Cache\Type as PageCache;
use Magento\Framework\App\CacheInterface;
use Magento\Framework\View\Asset\Repository as AssetRepository;

class CriticalCss implements CriticalCssInterface
{
    const CRIT_CSS_CACHE_LIFETIME = 2592000;

    /**
     * @var CacheInterface
     */
    protected $cache;
    /**
     * @var AssetRepository
     */
    protected $assetRepo;

    /**
     * @var
     */
    protected $minification;

    protected $availableCritical;

    /**
     * @param CacheInterface  $cache
     * @param AssetRepository $assetRepo
     */
    public function __construct(
        CacheInterface  $cache,
        AssetRepository $assetRepo,
        $availableCritical = []
    ) {
        $this->cache = $cache;
        $this->assetRepo = $assetRepo;
        $this->availableCritical = $availableCritical;
    }

    /**
     * @param $fileName
     * @return array|string|string[]
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function loadContent($fileName)
    {
        $content = $this->cache->load($fileName);
        if (!$content) {
            $asset = $this->assetRepo->createAsset(
                $fileName,
                ['_secure' => true]
            );
            try {
                $content = $asset->getContent();
                $content = str_replace(
                    'domain_static_version/',
                    $this->assetRepo->getStaticViewFileContext()->getBaseUrl(),
                    $content
                );
                $this->cache->save($content, $fileName, [PageCache::CACHE_TAG], static::CRIT_CSS_CACHE_LIFETIME);
            } catch (\Exception $e) {
                //By pass Exception.
                $content = '';
            }
        }
        return $content;
    }

    /**
     * @return string
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getDefaultCritical()
    {
        $defaultCss = $this->loadContent(CriticalCssInterface::DEFAULT_CRITICAL_CSS_FILE);
        $coreVitalCss = $this->loadContent(CriticalCssInterface::CORE_VITAL_CSS_FILE);
        return $defaultCss . PHP_EOL . $coreVitalCss;
    }

    /**
     * @return array|mixed|string|string[]
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getFontCritical()
    {
        $fileName = CriticalCssInterface::FONT_CRITICAL_CSS_FILE;
        return $this->loadContent($fileName);
    }

    /**
     * @param $bodyClass
     * @return string
     */
    public function getCriticalContent($bodyClass)
    {
        $availableCritical = $this->availableCritical;
        //['cms-index-index' => 'Hidro_CoreWebVitals::core_vital.css']
        foreach ($availableCritical as $class => $_fileId){
            if(strpos($bodyClass, $class) !== false){
                return $this->loadContent($_fileId);
            }
        }
        return '';
    }
}
