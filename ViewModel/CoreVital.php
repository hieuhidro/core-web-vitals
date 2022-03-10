<?php
/**
 * Created by Hidro Le.
 * Email: hieu@solutiontutorials.com
 * Date: 19/08/2021
 * Time: 11:48
 */

namespace Hidro\CoreWebVitals\ViewModel;

use Magento\Framework\View\Element\Block\ArgumentInterface;
use Hidro\CoreWebVitals\Helper\Data;

class CoreVital implements ArgumentInterface
{
    /**
     * @var Data
     */
    protected $helper;

    public function __construct(Data $helper)
    {
        $this->helper = $helper;
    }

    public function getDeferCSSMode(){
        return $this->helper->getDeferCSSMode();
    }
}
