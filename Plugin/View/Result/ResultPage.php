<?php

/**
 * Created by Hidro Le.
 * Email: hieu@solutiontutorials.com
 * Date: 19/08/2021
 * Time: 22:33
 */

namespace Hidro\CoreWebVitals\Plugin\View\Result;

use \Hidro\CoreWebVitals\Model\AssetService;
use Zend\Http\Header\GenericMultiHeader;
use Zend\Http\HeaderLoader;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\View\Result\Page;
use \Magento\Framework\App\Response\Http as HttpResponse;

class ResultPage
{
    protected $assetService;

    public function __construct(
        AssetService $assetService
    ) {
        $this->assetService = $assetService;
    }

    /**
     * @param \Magento\Framework\View\Result\Page $subject
     * @param                                     $result
     * @param ResponseInterface                   $httpResponse
     */
    public function afterRenderResult(
        \Magento\Framework\Controller\ResultInterface $subject,
                                                      $result,
        ResponseInterface                             $response
    )
    {
        if ($response instanceof  HttpResponse) {
            $headers = $response->getHeaders();
            $this->assetService->pushPreloadHeader($headers);
        }
        return $result;
    }
}
