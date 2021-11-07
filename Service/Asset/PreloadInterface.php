<?php
/**
 * Created by Hidro Le.
 * Email: hieu@solutiontutorials.com
 * Date: 19/08/2021
 * Time: 21:46
 */

namespace Hidro\CoreWebVitals\Service\Asset;

use Zend\Http\Header\GenericMultiHeader;
use Zend\Http\Headers;

interface PreloadInterface
{
    const TYPE_CSS = 'style';
    const TYPE_JS = 'script';

    /**
     * @param $file
     * @return boolean
     */
    public function isPreload($file);

    /**
     * @param $url
     * @param $type
     * @return GenericMultiHeader[]|GenericMultiHeader
     */
    public function getHeader($url, $type);

    /**
     * @param Headers $headers
     * @return \Iterator
     */
    public function registerPlugin($headers);

    /**
     * @param Headers $headers
     * @return \Iterator
     */
    public function appendPreload($headers);

    /**
     * register file to static
     * @param $url
     * @param $type
     * @return PreloadInterface
     */
    public function registerFile($url, $type);

    /**
     * return array [$type => [$files]]
     * @return string[]
     */
    public function getPreloadFiles();

    /**
     * @return string[]
     */
    public function getSupportTypes();
}
