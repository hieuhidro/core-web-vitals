<?php
/**
 * Created by Hidro Le.
 * Job Title: Solution Manager
 * Company: FORIX
 * Date: 10/11/2021
 * Time: 21:43
 */

namespace Hidro\CoreWebVitals\Model\Model\Config\Source;

use Hidro\CoreWebVitals\Service\Asset\DeferCSSInterface;

class DeferCSSMode implements \Magento\Framework\Option\ArrayInterface
{
    public function toOptionArray()
    {
        $optionArray[] = [
            'value' => 0,
            'label' => __("Disable"),
        ];

        $optionArray[] = [
            'value' => DeferCSSInterface::DEFAULT_BROWSER,
            'label' => __("Default Browser rel='preload'"),
        ];

        $optionArray[] = [
            'value' => DeferCSSInterface::JAVASCRIPT_PRELOAD,
            'label' => __("Javascript rel='cwv_preload'"),
        ];

        return $optionArray;
    }
}
