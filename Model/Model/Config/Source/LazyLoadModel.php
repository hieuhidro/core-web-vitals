<?php
/**
 * Created by Hidro Le.
 * Email: hieu@solutiontutorials.com
 * Date: 03/10/2021
 * Time: 18:49
 */

namespace Hidro\CoreWebVitals\Model\Model\Config\Source;

class LazyLoadModel implements \Magento\Framework\Option\ArrayInterface
{
    const DEFAULT_BROWSER = 1;
    const JAVASCRIPT_LAZY = 2;

    public function toOptionArray()
    {
        $optionArray[] = [
            'value' => 0,
            'label' => __("Disable"),
        ];

        $optionArray[] = [
            'value' => self::DEFAULT_BROWSER,
            'label' => __("Default Browser loading='lazy'"),
        ];

        $optionArray[] = [
            'value' => self::JAVASCRIPT_LAZY,
            'label' => __("Javascript Lazy Loading"),
        ];

        return $optionArray;
    }
}
