<?php
/**
 * Created by Hidro Le.
 * Email: hieu@solutiontutorials.com
 * Date: 03/10/2021
 * Time: 21:06
 */

namespace Hidro\CoreWebVitals\Model\Html;

use \Hidro\CoreWebVitals\Helper\Data;

class Context
{
    protected $helperData;
    public function __construct(Data $helperData)
    {
        $this->helperData = $helperData;
    }

    /**
     * @return Data
     */
    public function getHelper()
    {
        return $this->helperData;
    }
}
