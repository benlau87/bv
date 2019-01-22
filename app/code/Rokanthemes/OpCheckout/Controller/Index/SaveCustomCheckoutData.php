<?php

namespace Rokanthemes\OpCheckout\Controller\Index;

class SaveCustomCheckoutData extends \Magento\Framework\App\Action\Action
{
    protected $_sidebar;
	
    protected $_resultJsonFactory;
	
    protected $_jsonHelper;

    protected $_dataObjectFactory;
   
    protected $_cartTotalRepositoryInterface;

    protected $_oscHelper;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\Json\Helper\Data $jsonHelper,
        \Magento\Framework\DataObjectFactory $dataObjectFactory,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Magento\Quote\Api\CartTotalRepositoryInterface $cartTotalRepositoryInterface,
        \Magento\Checkout\Model\Sidebar $sidebar,
        \Rokanthemes\OpCheckout\Helper\Data $oscHelper
    ) {
        parent::__construct($context);
        $this->_resultJsonFactory = $resultJsonFactory;
        $this->_jsonHelper = $jsonHelper;
        $this->_dataObjectFactory = $dataObjectFactory;
        $this->_sidebar = $sidebar;
        $this->_cartTotalRepositoryInterface = $cartTotalRepositoryInterface;
        $this->_oscHelper = $oscHelper;
    }

    public function execute()
    {
        $additionalData = $this->_dataObjectFactory->create([
            'data' => $this->_jsonHelper->jsonDecode($this->getRequest()->getContent()),
        ]);
		$this->_objectManager->get('Magento\Checkout\Model\Session')->setData('osc_newsletter', $additionalData->getData('osc_newsletter'));
    }
}