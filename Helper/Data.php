<?php

/**
 * Created by Hidro Le.
 * Job Title: Magento Developer
 * Project Name: local.perfectcircuit.com
 * Date: 27/06/2021
 * Time: 22:40
 */

namespace Hidro\CoreWebVitals\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use \Magento\Store\Model\ScopeInterface;

class Data extends AbstractHelper
{
    const CORE_WEB_VITAL_GENERAL_CONFIG_XML = 'cwv/';

    public function getConfig($path, $scope = ScopeInterface::SCOPE_STORE)
    {
        return $this->scopeConfig->getValue(self::CORE_WEB_VITAL_GENERAL_CONFIG_XML . $path, $scope);
    }

    public function isEnable()
    {
        return !!$this->getConfig('general/enable');
    }

    public function isDebug()
    {
        return !!$this->getConfig('general/debug');
    }

    public function isMoveJsToFooter()
    {
        return !!$this->getConfig('js/move_to_footer');
    }

    public function isMinifyInlineJs()
    {
        return !!$this->getConfig('js/minify_inline');
    }

    public function isLoadFirstOwl()
    {
        return !!$this->getConfig('js/owl_loaded');
    }

    public function isMoveCSSToFooter()
    {
        return !!$this->getConfig('css/move_to_footer');
    }

    public function listOfMoveFooterCSS()
    {
        return array_map('trim', explode(',', $this->getConfig('css/move_footer_files')));
    }

    public function isMinifyInlineCSS()
    {
        return !!$this->getConfig('css/minify_inline');
    }

    public function isMinifyHTML()
    {
        return !!$this->getConfig('html/enable_minify');
    }

    public function isLazyLoadingImage()
    {
        return $this->getConfig('html/lazy_loading_image');
    }

    public function isLazyLoadingIframe()
    {
        return $this->getConfig('html/lazy_loading_iframe');
    }

    public function isServerPushCSS()
    {
        return !!$this->getConfig('css/css_server_push');
    }

    public function getServerPushCSSFiles()
    {
        return array_map('trim', explode(',', $this->getConfig('css/server_push_files')));
    }

    public function getDeferCSSMode()
    {
        return $this->getConfig('css/defer_mode');
    }

    public function isEnableCritical()
    {
        return !!$this->getConfig('css/enable_critical');
    }

    public function getDeferCSSFiles()
    {
        return array_map('trim', explode(',', $this->getConfig('css/defer_files')));
    }

    public function isServerPushJS()
    {
        return !!$this->getConfig('js/js_server_push');
    }

    public function getServerPushJSFiles()
    {
        return array_map('trim', explode(',', $this->getConfig('js/server_push_files')));
    }

    public function isDeferRecaptcha()
    {
        return !!$this->getConfig('js/recaptcha');
    }
}
