<?php

/**
 * Created by Hidro Le.
 * Email: hieu@solutiontutorials.com
 * Date: 21/08/2021
 * Time: 01:34
 */

namespace Hidro\CoreWebVitals\Plugin\Controller;

use Hidro\CoreWebVitals\Service\Html\OutputModifierInterface;
use Magento\Framework\App\Response\Http as ResponseHttp;
use Magento\Framework\App\ResponseInterface;

class ControllerResultPlugin
{

    /**
     * @var OutputModifierInterface
     */
    protected $outputModifier;
    /**
     * @var \Magento\Framework\Registry
     */
    protected $registry;

    /**
     * @param \Magento\Framework\Registry $registry
     * @param OutputModifierInterface     $outputModifier
     */
    public function __construct(
        \Magento\Framework\Registry $registry,
        OutputModifierInterface     $outputModifier
    ) {
        $this->registry = $registry;
        $this->outputModifier = $outputModifier;
    }

    /**
     * @param \Magento\Framework\Controller\ResultInterface $subject
     * @param                                               $result
     * @param ResponseInterface                             $response
     */
    public function afterRenderResult(
        \Magento\Framework\Controller\ResultInterface $subject,
                                                      $result,
        ResponseInterface                             $response
    ) {
        $usePlugin = $this->registry->registry('use_page_cache_plugin');
        if ($usePlugin) {
            if ($response instanceof ResponseHttp) {
                $content = $response->getBody();
                $content = $this->outputModifier->modify($content);
                $response->setBody($content);
            }
        }
        return $result;
    }
}
