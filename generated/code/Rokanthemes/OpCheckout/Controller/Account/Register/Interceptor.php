<?php
namespace Rokanthemes\OpCheckout\Controller\Account\Register;

/**
 * Interceptor class for @see \Rokanthemes\OpCheckout\Controller\Account\Register
 */
class Interceptor extends \Rokanthemes\OpCheckout\Controller\Account\Register implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Action\Context $context, \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory, \Magento\Framework\Controller\Result\RawFactory $resultRawFactory, \Magento\Customer\Model\Session $customerSession, \Magento\Framework\Json\Helper\Data $jsonHelper, \Magento\Framework\DataObjectFactory $dataObjectFactory, \Magento\Customer\Api\AccountManagementInterface $accountManagement, \Magento\Customer\Model\CustomerExtractor $customerExtractor, \Magento\Newsletter\Model\SubscriberFactory $subscriberFactory, \Magento\Customer\Model\Url $customerUrl, \Magento\Customer\Model\Session $session, \Magento\Customer\Api\Data\CustomerInterfaceFactory $customerFactory)
    {
        $this->___init();
        parent::__construct($context, $resultJsonFactory, $resultRawFactory, $customerSession, $jsonHelper, $dataObjectFactory, $accountManagement, $customerExtractor, $subscriberFactory, $customerUrl, $session, $customerFactory);
    }

    /**
     * {@inheritdoc}
     */
    public function dispatch(\Magento\Framework\App\RequestInterface $request)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'dispatch');
        if (!$pluginInfo) {
            return parent::dispatch($request);
        } else {
            return $this->___callPlugins('dispatch', func_get_args(), $pluginInfo);
        }
    }
}
