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
        return true; //Always enable this.
    }

    /**
     * @inheritDoc
     */
    public function modify($html)
    {
        //Process <background-image> tag
        $_html = preg_replace_callback(
            '/background-image\s*?:\s*?url.*?>/is',
            function ($matches) {
                $content = $matches[0];
                $search = ['>'];
                if (false !== preg_match('/url\s*?\((.*?)\)/is', $content, $match)) {
                    if (count($match) > 1) {
                        $imageUrl = trim($match[1], '"\'');
                        $replace = [sprintf('><img alt="" src="%s" style="display: none"/>', $imageUrl)];
                        return str_replace($search, $replace, $content);
                    }
                }
                return $content;
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
