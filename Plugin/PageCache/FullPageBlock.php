<?php

namespace Hidro\CoreWebVitals\Plugin\PageCache;

use Hidro\CoreWebVitals\Service\Html\OutputModifierInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\PageCache\Controller\Block;

class FullPageBlock
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
     * @param Block             $subject
     * @param ResponseInterface|null $result
     * @return ResponseInterface|null
     */
    public function afterExecute(Block $subject, $result = null)
    {
        /**
         * Fixing Varnish cache issue.
         */
        $response = $subject->getResponse();
        $content = $response->getContent();
        if ($this->outputModifier->isEnabled()) {
            $content = $this->outputModifier->modify($content);
            $response->setContent($content);
        }
        return $result;
    }
}
