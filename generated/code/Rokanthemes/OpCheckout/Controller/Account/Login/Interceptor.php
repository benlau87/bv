<?php
namespace Rokanthemes\OpCheckout\Controller\Account\Login;

/**
 * Interceptor class for @see \Rokanthemes\OpCheckout\Controller\Account\Login
 */
class Interceptor extends \Rokanthemes\OpCheckout\Controller\Account\Login implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Action\Context $context, \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory, \Magento\Framework\Controller\Result\RawFactory $resultRawFactory, \Magento\Customer\Model\Session $customerSession, \Magento\Framework\Json\Helper\Data $jsonHelper, \Magento\Framework\DataObjectFactory $dataObjectFactory, \Magento\Customer\Api\AccountManagementInterface $accountManagement)
    {
        $this->___init();
        parent::__construct($context, $resultJsonFactory, $resultRawFactory, $customerSession, $jsonHelper, $dataObjectFactory, $accountManagement);
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
