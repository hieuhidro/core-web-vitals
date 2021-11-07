<?php

/**
 * Created by Hidro Le.
 * Email: hieu@solutiontutorials.com
 * Date: 21/08/2021
 * Time: 00:49
 */

namespace Hidro\CoreWebVitals\Plugin\App;

use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\Response\Http as ResponseHttp;
use \Magento\Framework\View\Result\Page as ResultPage;
use Hidro\CoreWebVitals\Service\Html\OutputModifierInterface;

class FrontendController
{
    /**
     * @var OutputModifierInterface
     */
    protected $outputModifier;

    /**
     * @param OutputModifierInterface $outputModifier
     */
    public function __construct(
        OutputModifierInterface $outputModifier
    ) {
        $this->outputModifier = $outputModifier;
    }

    /**
     * @param \Magento\Framework\App\FrontControllerInterface $subject
     * @param                                                 $result
     * @param RequestInterface                                $request
     */
    public function afterDispatch(
        \Magento\Framework\App\FrontControllerInterface $subject,
        $result,
        RequestInterface                                $request
    ) {
        if ($result instanceof ResponseHttp) {
            $content = $result->getContent();
            $content = $this->outputModifier->modify($content);
            $result->setContent($content);
        }
        return $result;
    }
}
