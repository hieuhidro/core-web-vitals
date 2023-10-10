<?php

/**
 * Created by Hidro Le.
 * Email: hieu@solutiontutorials.com
 * Date: 21/08/2021
 * Time: 01:49
 */

namespace Hidro\CoreWebVitals\Model\Html\Modifier;

use Hidro\CoreWebVitals\Model\Html\Context;
use Hidro\CoreWebVitals\Model\Model\Config\Source\LazyLoadModel;

class LazyLoadImage extends AbstractModifier
{
    public function isEnabled()
    {
        return !!$this->helper->isLazyLoadingImage();
    }

    /**
     * @return string[]
     */
    public function excludeImageFromDomains()
    {
        return [
            'facebook.com',
            'gallery-placeholder__image',
            'cwv-skip'
        ];
    }

    /**
     * @inheritDoc
     */
    public function modify($html)
    {
        //Process <img> tag
        $excludeDomains = $this->excludeImageFromDomains();
        $_html = preg_replace_callback(
            '#<img(?:\s+[-\w]+=(?:"[^"]*"|\'[^\']*\'))+\s*(?:/|)>#mu',
            function ($matches) use ($excludeDomains) {
                $img = $matches[0];
                foreach ($excludeDomains as $domain) {
                    if (strpos($img, $domain) !== false) {
                        return $img;
                    }
                }
                $search = [' src='];
                switch ($this->helper->isLazyLoadingImage()) {
                    case LazyLoadModel::JAVASCRIPT_LAZY:
                        $defaultImage = 'data:image/gif;base64,R0lGODlhAQABAAD/ACwAAAAAAQABAAACADs=';
                        $replace = [' onload="this.onload=null, window.CWVLazyLoad({}, this);"
                         onerror="this.onerror=null,window.CWVLazyLoad({}, this);" src="' .
                                    ($defaultImage) . '" data-src='];
                        break;
                    default:
                        $replace = [' loading="lazy" src='];
                        break;
                }
                return str_replace($search, $replace, $img);
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
