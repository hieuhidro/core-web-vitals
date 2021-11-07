<?php
/**
 * Created by Hidro Le.
 * Email: hieu@solutiontutorials.com
 * Date: 19/08/2021
 * Time: 21:46
 */

namespace Hidro\CoreWebVitals\Service\Asset;

use Magento\Framework\View\Asset\AssetInterface;

interface FooterCSSInterface
{
    /**
     * @param string $file
     * @return boolean
     */
    public function canMove($fileId);

    /**
     * register file to static
     *
     * @param string         $fileId
     * @param AssetInterface $asset
     * @return FooterCSSInterface
     */
    public function registerFile($fileId, $asset);

    /**
     * return array [$type => [$files]]
     *
     * @return string[]
     */
    public function getFooterAssetFiles();
}
