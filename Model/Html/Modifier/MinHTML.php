<?php

/**
 * Created by Hidro Le.
 * Email: hieu@solutiontutorials.com
 * Date: 21/08/2021
 * Time: 17:53
 */

namespace Hidro\CoreWebVitals\Model\Html\Modifier;

use Hidro\CoreWebVitals\Model\Html\Context;
use Hidro\CoreWebVitals\Service\Asset\MinificationInterface;

class MinHTML extends AbstractModifier
{

    /**
     * @var MinificationInterface
     */
    protected $minification;

    /**
     * @param MinificationInterface $minification
     */
    public function __construct(
        Context               $context,
        MinificationInterface $minification
    ) {
        $this->minification = $minification;
        parent::__construct($context);
    }

    public function isEnabled()
    {
        return $this->helper->isMinifyHTML();
    }

    /**
     * @param string $html
     * @return array|string|string[]|null
     */
    public function modify($html)
    {
        $_html = $this->minification->minify($html);
        //If preg_replace_callback has an error. revert the html
        if (null !== $_html) {
            $html = $_html;
        }
        return $html;
    }
}
