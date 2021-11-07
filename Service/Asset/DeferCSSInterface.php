<?php
/**
 * Created by Hidro Le.
 * Email: hieu@solutiontutorials.com
 * Date: 19/08/2021
 * Time: 21:46
 */

namespace Hidro\CoreWebVitals\Service\Asset;

interface DeferCSSInterface
{
    /**
     * @param $file
     * @return boolean
     */
    public function isDefer($file);

    /**
     * register file to static
     * @param $url
     * @param $attribute
     * @return PreloadInterface
     */
    public function registerFile($url, $attribute);

    /**
     * @return string
     */
    public function renderDeferFiles();

}
