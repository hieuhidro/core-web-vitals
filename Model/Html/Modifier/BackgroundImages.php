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

class BackgroundImages extends AbstractModifier
{
    public function isEnabled()
    {
        return false; //Always disable this.
    }

    /**
     * @inheritDoc
     */
    public function modify($html)
    {
        //Process <background-image> tag
        $imageUrls = [];
        $_html = preg_replace_callback(
            '/background-image\s*?:\s*?url.*?([\'\"]\))/is',
            function ($matches) use (&$imageUrls){
                $content = $matches[0];
                if (false !== preg_match('/url\s*?\((.*?)\)/is', $content, $match)) {
                    if (count($match) > 1) {
                        $imageUrl = trim($match[1], '"\'');
                        if(!in_array($imageUrl, $imageUrls)) {
                            $imageUrls[] = $imageUrl;
                        }
                    }
                }
                return $matches[0]; //Won't replace anything.
            },
            $html
        );
        if($imageUrls && null !== $_html){
            $content = '';
            foreach ($imageUrls as $imageUrl){
                $content .= sprintf('<link rel="preload" as="image" href="%s" />', $imageUrl);
            }
            $search = '<style data-type="criticalCss"';
            $_html = str_replace($search, $content . $search, $_html);
            $html = $_html;
        }
        return $html;
    }
}
