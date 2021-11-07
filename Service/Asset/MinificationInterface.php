<?php
/**
 * Created by Hidro Le.
 * Email: hieu@solutiontutorials.com
 * Date: 20/08/2021
 * Time: 20:05
 */

namespace Hidro\CoreWebVitals\Service\Asset;

interface MinificationInterface
{
    /**
     * @param string $content
     * @return string
     */
    public function minify($content);
}
