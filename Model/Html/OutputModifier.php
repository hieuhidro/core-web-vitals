<?php

/**
 * Created by Hidro Le.
 * Email: hieu@solutiontutorials.com
 * Date: 21/08/2021
 * Time: 00:10
 */

namespace Hidro\CoreWebVitals\Model\Html;

use Hidro\CoreWebVitals\Model\Html\Modifier\AbstractModifier;
use Hidro\CoreWebVitals\Service\Html\OutputModifierInterface;
use Psr\Log\LoggerInterface;

class OutputModifier extends AbstractModifier
{
    /**
     * @var OutputModifierInterface[]
     */
    protected $modifiers;
    /**
     * @var bool
     */
    protected $_isDebug = false;
    /**
     * @var LoggerInterface
     */
    protected $_logger;
    /**
     * @var string
     */
    protected $_uniqId;

    /**
     * @param LoggerInterface           $logger
     * @param OutputModifierInterface[] $modifiers
     */
    public function __construct(
        Context $context,
        LoggerInterface $logger,
        $modifiers = []
    ) {
        parent::__construct($context);
        $this->modifiers = $modifiers;
        $this->_logger = $logger;
        $this->_isDebug = $this->helper->isDebug();
        if ($this->_isDebug) {
            $this->_uniqId = uniqid('Modifier_');
        }
    }

    public function isEnabled(){
        return $this->helper->isEnable();
    }

    /**
     * @param string $html
     * @return string
     */
    public function modify($html)
    {
        if ($this->isEnabled()) {
            if (strpos($html, '</body') !== false) {
                foreach ($this->modifiers as $name => $modifier) {
                    if ($modifier instanceof OutputModifierInterface) {
                        if ($modifier->isEnabled()) {
                            $start_time = microtime(true);
                            $html = $modifier->modify($html);
                            $end_time = microtime(true);
                            if ($this->_isDebug) {
                                // Calculate the script execution time
                                $execution_time = ($end_time - $start_time);
                                $this->_logger->info($this->_uniqId . ":" . $name . ": " . $execution_time . " seconds");
                            }
                        }
                    }
                }
            }
        }
        return $html;
    }
}
