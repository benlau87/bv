<?php

namespace Rokanthemes\Themeoption\Observer;

use Magento\Framework\Event\ObserverInterface;

class Savethemeoption implements ObserverInterface
{
    protected $_css;
    
    public function __construct(
        \Rokanthemes\Themeoption\Model\Custom\Generator $css
    ) {
        $this->_css = $css;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $this->_css->generateCss($observer->getData("website"), $observer->getData("store"));
    }
}
