<?php

namespace Rokanthemes\OpCheckout\Controller\Account;

use Magento\Customer\Model\AccountManagement;
use Magento\Customer\Model\Session;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Store\Model\StoreManagerInterface;

class ForgotPassword extends \Magento\Customer\Controller\AbstractAccount
{
    protected $customerAccountManagement;

    protected $escaper;

    protected $session;

    protected $storeManager;

    protected $_dataObjectFactory;
	
    protected $_jsonHelper;

    protected $resultJsonFactory;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Customer\Model\AccountManagement $customerAccountManagement,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Magento\Framework\DataObjectFactory $dataObjectFactory,
        \Magento\Framework\Json\Helper\Data $jsonHelper,
        \Magento\Framework\Escaper $escaper
    )
    {
        $this->session = $customerSession;
        $this->customerAccountManagement = $customerAccountManagement;
        $this->_dataObjectFactory = $dataObjectFactory;
        $this->escaper = $escaper;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->storeManager = $storeManager;
        $this->_jsonHelper = $jsonHelper;
        parent::__construct($context);
    }

    public function execute()
    {
        if (!$this->getRequest()->isXmlHttpRequest()) {
            return $this;
        }
        
        $result = [];
        $result['success'] = '';
        $result['errorMessage'] = '';
        $result['successMessage'] = '';

        $resultJson = $this->resultJsonFactory->create();
        $paramsData = $this->_dataObjectFactory->create([
            'data' => $this->_jsonHelper->jsonDecode($this->getRequest()->getContent()),
        ]);
        $email = $paramsData->getData('email');
        $websiteId = $this->storeManager->getStore()->getWebsiteId();
        if ($email) {
            if (!\Zend_Validate::is($email, 'EmailAddress')) {
                $this->session->setForgottenEmail($email);
                $result['success'] = 'false';
                $result['errorMessage'] = __('Please correct the email address.');
                $resultJson = $this->resultJsonFactory->create();

                return $resultJson->setData($result);
            } else {
                $customer = $this->_objectManager->create('Magento\Customer\Model\Customer')
                    ->setWebsiteId($websiteId)
                    ->loadByEmail($email);
                if ($customer->getId()) {
                    try {
                        $this->customerAccountManagement->initiatePasswordReset(
                            $email,
                            AccountManagement::EMAIL_RESET
                        );
                    } catch (NoSuchEntityException $e) {
                        $this->_objectManager->get('Psr\Log\LoggerInterface')->notice($e->getMessage());
                    } catch (\Exception $exception) {
                        $this->messageManager->addExceptionMessage(
                            $exception,
                            __('We\'re unable to send the password reset email.')
                        );
                        $result['success'] = 'false';
                        $result['errorMessage'] = __('We\'re unable to send the password reset email.');

                        return $resultJson->setData($result);
                    }
                    $result['success'] = 'true';
                    $result['successMessage'] = $this->getSuccessMessage($email);

                    return $resultJson->setData($result);
                } else {
                    $result = ['success' => false, 'errorMessage' => 'The account does not exist.'];

                    return $resultJson->setData($result);
                }
            }
        } else {
            $result['success'] = 'false';
            $result['errorMessage'] = __('Please enter your email');

            return $resultJson->setData($result);
        }
    }

    protected function getSuccessMessage($email)
    {
        return __(
            'If there is an account associated with %1 you will receive an email with a link to reset your password.',
            $this->escaper->escapeHtml($email)
        );
    }
}
