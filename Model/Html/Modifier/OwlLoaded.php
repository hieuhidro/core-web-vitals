<?php

/**
 * Created by Hidro Le.
 * Job Title: Magento Developer
 * Project Name: local.vintage-king.com
 * Date: 24/06/2021
 * Time: 19:21
 */

namespace Hidro\CoreWebVitals\Model\Html\Modifier;

class OwlLoaded extends AbstractModifier
{
    public function isEnabled()
    {
        return $this->helper->isLoadFirstOwl();
    }

    public function modify($html)
    {
        $_html = preg_replace_callback(
            '/class\s*=\s*["\'][\w\s\-\.]*?owl-carousel[\w\s\-\.]+/is',
            function ($matches) {
                return str_replace(' owl-carousel ', ' owl-carousel cwv-owl ', $matches[0]);
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
