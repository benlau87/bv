<?php

namespace Rokanthemes\OpCheckout\Controller\Account;

use Magento\Customer\Api\AccountManagementInterface;
use Magento\Framework\Exception\EmailNotConfirmedException;
use Magento\Framework\Exception\InvalidEmailOrPasswordException;

class Login extends \Magento\Framework\App\Action\Action
{
    
    protected $_customerAccountManagement;
    
    protected $_resultJsonFactory;

    protected $_resultRawFactory;

    protected $_customerSession;

    protected $_dataObjectFactory;

    protected $_jsonHelper;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Magento\Framework\Controller\Result\RawFactory $resultRawFactory,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Framework\Json\Helper\Data $jsonHelper,
        \Magento\Framework\DataObjectFactory $dataObjectFactory,
        \Magento\Customer\Api\AccountManagementInterface $accountManagement
    ) {
        parent::__construct($context);
        $this->_resultJsonFactory = $resultJsonFactory;
        $this->_resultRawFactory = $resultRawFactory;
        $this->_customerAccountManagement = $accountManagement;
        $this->_customerSession = $customerSession;
        $this->_dataObjectFactory = $dataObjectFactory;
        $this->_jsonHelper = $jsonHelper;
    }

    public function execute()
    {
        $credentials = null;
        $httpBadRequestCode = 400;

        $resultRaw = $this->_resultRawFactory->create();
        try {
            $paramsData = $this->_getParamDataObject();

            $username = $paramsData->getData('username');
            $password = $paramsData->getData('password');
            $credentials['username'] = $username;
            $credentials['password'] = $password;
        } catch (\Exception $e) {
            return $resultRaw->setHttpResponseCode($httpBadRequestCode);
        }
        if (!$credentials || $this->getRequest()->getMethod() !== 'POST' || !$this->getRequest()->isXmlHttpRequest()) {
            return $resultRaw->setHttpResponseCode($httpBadRequestCode);
        }

        $response = [
            'errors'  => false,
            'message' => __('Login successful.'),
        ];
        try {
            $customer = $this->_customerAccountManagement->authenticate(
                $credentials['username'],
                $credentials['password']
            );
            $this->_customerSession->setCustomerDataAsLoggedIn($customer);
            $this->_customerSession->regenerateId();
        } catch (EmailNotConfirmedException $e) {
            $response = [
                'errors'  => true,
                'message' => $e->getMessage(),
            ];
        } catch (InvalidEmailOrPasswordException $e) {
            $response = [
                'errors'  => true,
                'message' => $e->getMessage(),
            ];
        } catch (\Exception $e) {
            $response = [
                'errors'  => true,
                'message' => __('Invalid login or password.'),
            ];
        }

        $resultJson = $this->_resultJsonFactory->create();

        return $resultJson->setData($response);
    }

    protected function _getParamDataObject()
    {
        return $this->_dataObjectFactory->create([
            'data' => $this->_jsonHelper->jsonDecode($this->getRequest()->getContent()),
        ]);
    }

}
