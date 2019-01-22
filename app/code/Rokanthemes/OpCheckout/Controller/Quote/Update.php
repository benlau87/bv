<?php

namespace Rokanthemes\OpCheckout\Controller\Quote;

class Update extends \Magento\Framework\App\Action\Action {
    
    protected $_sidebar;
   
    protected $_resultJsonFactory;

    protected $_jsonHelper;

    protected $_dataObjectFactory;
  
    protected $_cartTotalRepositoryInterface;

    protected $_oscHelper;

    protected $cart;

    protected $_configHelper;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\Json\Helper\Data $jsonHelper,
        \Magento\Framework\DataObjectFactory $dataObjectFactory,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Magento\Quote\Api\CartTotalRepositoryInterface $cartTotalRepositoryInterface,
        \Magento\Checkout\Model\Sidebar $sidebar,
        \Rokanthemes\OpCheckout\Helper\Data $oscHelper,
        \Rokanthemes\OpCheckout\Helper\Config $configHelper,
        \Magento\Checkout\Model\Cart $cart
    ) {
        parent::__construct($context);
        $this->_resultJsonFactory = $resultJsonFactory;
        $this->_jsonHelper = $jsonHelper;
        $this->_dataObjectFactory = $dataObjectFactory;
        $this->_sidebar = $sidebar;
        $this->_cartTotalRepositoryInterface = $cartTotalRepositoryInterface;
        $this->_oscHelper = $oscHelper;
        $this->_configHelper = $configHelper;
        $this->cart = $cart;
    }

    public function execute()
    {
        $qtyData = $this->_dataObjectFactory->create([
            'data' => $this->_jsonHelper->jsonDecode($this->getRequest()->getContent()),
        ]);
        
        $updateType = $qtyData->getData('updateType');
        $result = array();
        $result['error'] = '';
        try {
            if ($updateType == 'update') {
                $this->_sidebar->checkQuoteItem($qtyData->getData('itemId'));
                $this->_sidebar->updateQuoteItem($qtyData->getData('itemId'), $qtyData->getData('qty'));
            } else {
                $this->_sidebar->removeQuoteItem($qtyData->getData('itemId'));
            }

        } catch (\Exception $e) {
            $result['error'] = $e->getMessage();
        }

        if($this->cart->getSummaryQty() == 0){
            $result['cartEmpty'] = true;
        }

        if ($this->cart->getQuote()->isVirtual()) {
            $result['is_virtual'] = true;
        } else {
            $result['is_virtual'] = false;
        }
        
        $resultJson = $this->_resultJsonFactory->create();
        return $resultJson->setData($result);
    }
}