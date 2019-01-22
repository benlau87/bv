<?php

namespace Rokanthemes\OpCheckout\Controller\Account;

class Logout extends \Magento\Framework\App\Action\Action
{
    protected $_customerSession;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Customer\Model\Session $customerSession
    ) {
        parent::__construct($context);
        $this->_customerSession = $customerSession;
    }
	
    public function execute()
    {
        $this->_customerSession->logout();
    }


}
