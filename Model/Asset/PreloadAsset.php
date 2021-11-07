<?php

/**
 * Created by Hidro Le.
 * Email: hieu@solutiontutorials.com
 * Date: 19/08/2021
 * Time: 17:54
 */

namespace Hidro\CoreWebVitals\Model\Asset;

use Hidro\CoreWebVitals\Service\Asset\PreloadInterface;
use Zend\Http\Header\GenericMultiHeader;
use Zend\Http\HeaderLoader;
use Zend\Http\Headers;
use Hidro\CoreWebVitals\Model\Html\Context as CoreWebVitalsContext;

class PreloadAsset implements PreloadInterface
{
    /**
     * @var array|mixed
     */
    protected $preLoads;
    protected $helper;

    private static $preloadFiles = [];

    /**
     * @param array $preLoads
     */
    public function __construct(
        CoreWebVitalsContext $context
    ) {
        $this->helper = $context->getHelper();
    }

    protected function getCSSPreload()
    {
        $cssPreloadFiles = [];
        if ($this->helper->isServerPushCSS()) {
            $cssPreloadFiles = $this->helper->getServerPushCSSFiles();
        }
        return $cssPreloadFiles;
    }

    protected function getJsPreload()
    {
        $jsPreloadFiles = [];
        if ($this->helper->isServerPushJS()) {
            $jsPreloadFiles = $this->helper->getServerPushJSFiles();
        }
        return $jsPreloadFiles;
    }

    /**
     * @return array|mixed
     */
    public function getPreLoads()
    {
        if (null === $this->preLoads) {
            $this->preLoads = [];
            $this->preLoads = array_merge($this->preLoads, $this->getCSSPreload());
            $this->preLoads = array_merge($this->preLoads, $this->getJsPreload());
        }
        return $this->preLoads;
    }

    /**
     * @param $url
     * @param $type
     * @return GenericMultiHeader[]|GenericMultiHeader
     */
    public function getHeader($url, $type)
    {
        return GenericMultiHeader::fromString(sprintf("link: <%s>; rel=preload; as=%s", $url, $type));
    }

    /**
     * @param $file
     * @return bool
     */
    public function isPreload($file)
    {
        foreach ($this->getPreLoads() as $key => $_file) {
            if ($_file == $file) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param $url
     * @param $type
     * @return string[]
     */
    public function registerFile($url, $type)
    {
        if ($type == 'js') {
            $type = PreloadInterface::TYPE_JS;
        } else {
            $type = PreloadInterface::TYPE_CSS;
        }
        if (!isset(self::$preloadFiles[$url])) {
            self::$preloadFiles[$url] = $type;
        }
        return self::$preloadFiles;
    }

    /**
     * @return string[]
     */
    public function getPreloadFiles()
    {
        return self::$preloadFiles;
    }

    /**
     * @param Headers $headers
     * @return \Iterator
     */
    public function registerPlugin($headers)
    {
        HeaderLoader::addStaticMap(['link' => GenericMultiHeader::class]);
        $headers->getPluginClassLoader()->registerPlugin('link', GenericMultiHeader::class);
        return $headers;
    }

    /**
     * @param Headers $headers
     * @return \Iterator
     */
    public function appendPreload($headers)
    {
        $preloadFiles = $this->getPreloadFiles();
        foreach ($preloadFiles as $file => $type) {
            $headers->addHeader($this->getHeader($file, $type));
        }
        return $headers;
    }

    /**
     * @return string[]
     */
    public function getSupportTypes()
    {
        return [
            'js',
            'css'
        ];
    }
}
