<?php

/**
 * Created by Hidro Le.
 * Email: hieu@solutiontutorials.com
 * Date: 21/08/2021
 * Time: 01:48
 */

namespace Hidro\CoreWebVitals\Model\Html\Modifier;

use Hidro\CoreWebVitals\Service\Html\OutputModifierInterface;
use Hidro\CoreWebVitals\Model\Html\Context;

abstract class AbstractModifier implements OutputModifierInterface
{
    protected $helper;

    public function __construct(Context $context)
    {
        $this->helper = $context->getHelper();
    }

    public function isEnabled()
    {
        return false;
    }
}
