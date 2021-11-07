<?php

/**
 * Created by Hidro Le.
 * Email: hieu@solutiontutorials.com
 * Date: 20/08/2021
 * Time: 20:10
 */

namespace Hidro\CoreWebVitals\Model\Asset;

use Hidro\CoreWebVitals\Service\Asset\MinificationInterface;
use Magento\Framework\Code\Minifier\AdapterInterface;

class Minify implements MinificationInterface
{
    /**
     * @var AdapterInterface
     */
    protected $adapter;

    /**
     * @param AdapterInterface $adapter
     */
    public function __construct(
        AdapterInterface $adapter
    ) {
        $this->adapter = $adapter;
    }

    /**
     * @param string $content
     * @return string
     */
    public function minify($content)
    {
        return $this->adapter->minify($content);
    }
}
