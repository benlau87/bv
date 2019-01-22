<?php

namespace Rokanthemes\OpCheckout\Controller\Account;

use Magento\Customer\Api\Data\CustomerInterface;

use Magento\Framework\Exception\StateException;
use Magento\Framework\Exception\InputException;
use Magento\Customer\Api\AccountManagementInterface;
use Magento\Framework\Exception\LocalizedException;

class Register extends \Magento\Framework\App\Action\Action
{
    protected $_customerAccountManagement;
    
    protected $_resultJsonFactory;

    protected $_resultRawFactory;

    protected $_customerSession;

    protected $_customerFactory;

    protected $_dataObjectFactory;

    protected $_jsonHelper;

    protected $customerExtractor;

    protected $accountManagement;

    protected $subscriberFactory;

    protected $customerUrl;

    protected $session;
	
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Magento\Framework\Controller\Result\RawFactory $resultRawFactory,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Framework\Json\Helper\Data $jsonHelper,
        \Magento\Framework\DataObjectFactory $dataObjectFactory,
        \Magento\Customer\Api\AccountManagementInterface $accountManagement,
        \Magento\Customer\Model\CustomerExtractor $customerExtractor,
        \Magento\Newsletter\Model\SubscriberFactory $subscriberFactory,
        \Magento\Customer\Model\Url $customerUrl,
        \Magento\Customer\Model\Session $session,
        \Magento\Customer\Api\Data\CustomerInterfaceFactory $customerFactory
    ) {
        parent::__construct($context);
        $this->_resultJsonFactory = $resultJsonFactory;
        $this->_resultRawFactory = $resultRawFactory;
        $this->accountManagement = $accountManagement;
        $this->_customerSession = $customerSession;
        $this->_dataObjectFactory = $dataObjectFactory;
        $this->_jsonHelper = $jsonHelper;
        $this->customerExtractor = $customerExtractor;
        $this->subscriberFactory = $subscriberFactory;
        $this->customerUrl = $customerUrl;
        $this->session = $session;
        $this->_customerFactory = $customerFactory;
    }
   
    public function execute()
    {
        
        $resultJson = $this->_resultJsonFactory->create();
        
        $paramsData = $this->_getParamDataObject();

        try {
            $customer = $this->_customerFactory->create();
            $password = $paramsData->getData('password');
            $confirmation = $paramsData->getData('password_confirmation');

            $this->checkPasswordConfirmation($password, $confirmation);

            $customer->setFirstname($paramsData->getData('firstname'));
            $customer->setLastname($paramsData->getData('lastname'));
            $customer->setEmail($paramsData->getData('email'));


            $customer = $this->accountManagement->createAccount($customer, $password, '');
            if ($this->getRequest()->getParam('is_subscribed', false)) {
                $this->subscriberFactory->create()->subscribeCustomerById($customer->getId());
            }
            $this->_eventManager->dispatch(
                'customer_register_success',
                ['account_controller' => $this, 'customer' => $customer]
            );

            $confirmationStatus = $this->accountManagement->getConfirmationStatus($customer->getId());
            if ($confirmationStatus === AccountManagementInterface::ACCOUNT_CONFIRMATION_REQUIRED) {
                $email = $this->customerUrl->getEmailConfirmationUrl($customer->getEmail());
                // @codingStandardsIgnoreStart
                $message = __(
                    'You must confirm your account. Please check your email for the confirmation link or <a href="%1">click here</a> for a new link.',
                    $email
                );

                $result = ['success' => false, 'error' => $message];
                return $resultJson->setData($result);
            } else {
                $this->session->setCustomerDataAsLoggedIn($customer);
                $result = ['success' => true];
                return $resultJson->setData($result);

            }
        } catch (StateException $e) {
            $result = ['success' => false, 'error' => $e->getMessage()];
            return $resultJson->setData($result);
        } catch (InputException $e) {
            $result = ['success' => false, 'error' => $e->getMessage()];
            return $resultJson->setData($result);
        } catch (LocalizedException $e) {
            $result = ['success' => false, 'error' => $e->getMessage()];
            return $resultJson->setData($result);
        } catch (\Exception $e) {
            $result = ['success' => false, 'error' => $e->getMessage()];
            return $resultJson->setData($result);
        }
    }

    protected function _getParamDataObject()
    {
        return $this->_dataObjectFactory->create([
            'data' => $this->_jsonHelper->jsonDecode($this->getRequest()->getContent()),
        ]);
    }
	
    protected function checkPasswordConfirmation($password, $confirmation)
    {
        if ($password != $confirmation) {
            throw new InputException(__('Please make sure your passwords match.'));
        }
    }


}
