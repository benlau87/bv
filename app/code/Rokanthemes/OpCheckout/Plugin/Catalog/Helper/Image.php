<?php

namespace Rokanthemes\OpCheckout\Plugin\Catalog\Helper;

class Image extends \Magento\Catalog\Helper\Image
{
    
    protected $_helperConfig;

    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Catalog\Model\Product\ImageFactory $productImageFactory,
        \Magento\Framework\View\Asset\Repository $assetRepo,
        \Magento\Framework\View\ConfigInterface $viewConfig,
        \Rokanthemes\OpCheckout\Helper\Config $helperConfig
    ) {
        $this->_helperConfig = $helperConfig;
        parent::__construct($context, $productImageFactory, $assetRepo, $viewConfig);
    }

    public function afterGetWidth(\Magento\Catalog\Helper\Image $subject, $result)
    {
        if ($this->getFullRequest() == 'checkout_index_index' && $this->_helperConfig->isEnabledOneStep()) {
            return 270;
        } else {
            return $result;
        }
    }

    public function afterGetHeight(\Magento\Catalog\Helper\Image $subject, $result)
    {
        if ($this->getFullRequest() == 'checkout_index_index' && $this->_helperConfig->isEnabledOneStep()) {
            return 270;
        } else {
            return $result;
        }
    }

    public function getFullRequest()
    {
        $routeName = $this->_getRequest()->getRouteName();
        $controllerName = $this->_getRequest()->getControllerName();
        $actionName = $this->_getRequest()->getActionName();
        return $routeName.'_'.$controllerName.'_'.$actionName;
    }
}