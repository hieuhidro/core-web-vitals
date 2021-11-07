<?php
/**
 * Created by Hidro Le.
 * Email: hieu@solutiontutorials.com
 * Date: 20/08/2021
 * Time: 23:56
 */

namespace Hidro\CoreWebVitals\Service\Html;

interface OutputModifierInterface
{
    /**
     * @param string $html
     * @return string
     */
    public function modify($html);

    /**
     * @return bool
     */
    public function isEnabled();
}
