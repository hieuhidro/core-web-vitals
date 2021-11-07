<?php

/**
 * Created by Hidro Le.
 * Email: hieu@solutiontutorials.com
 * Date: 21/08/2021
 * Time: 01:46
 */

namespace Hidro\CoreWebVitals\Model\Html\Modifier;

use Hidro\CoreWebVitals\Model\Model\Config\Source\LazyLoadModel;

class LazyLoadIframe extends AbstractModifier
{
    public function isEnabled()
    {
        return !!$this->helper->isLazyLoadingIframe();
    }

    /**
     * @param string $html
     * @return array|string|string[]|null
     */
    public function modify($html)
    {
        //Process Iframe Youtube
        $_html = preg_replace_callback(
            '/<iframe.*?>.*?<\/iframe>/is',
            function ($matches) {
                if (strpos($matches[0], 'googletagmanager') === false) {
                    $img = $matches[0];
                    $search = [' src='];
                    switch ($this->helper->isLazyLoadingIframe()) {
                        case LazyLoadModel::JAVASCRIPT_LAZY:
                            $replace = [' onload="window.CWVLazyLoad({}, this);" data-src='];
                            break;
                        default:
                            $replace = [' loading="lazy" src='];
                            break;
                    }
                    return str_replace($search, $replace, $img);
                }
                return $matches[0];
            },
            $html
        );
        //If preg_replace_callback has an error. revert the html
        if (null !== $_html) {
            $html = $_html;
        }
        return $html;
    }
}
