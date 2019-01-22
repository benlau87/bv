<?php
namespace Rokanthemes\OpCheckout\Controller\Account\ForgotPassword;

/**
 * Interceptor class for @see \Rokanthemes\OpCheckout\Controller\Account\ForgotPassword
 */
class Interceptor extends \Rokanthemes\OpCheckout\Controller\Account\ForgotPassword implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Action\Context $context, \Magento\Customer\Model\Session $customerSession, \Magento\Customer\Model\AccountManagement $customerAccountManagement, \Magento\Store\Model\StoreManagerInterface $storeManager, \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory, \Magento\Framework\DataObjectFactory $dataObjectFactory, \Magento\Framework\Json\Helper\Data $jsonHelper, \Magento\Framework\Escaper $escaper)
    {
        $this->___init();
        parent::__construct($context, $customerSession, $customerAccountManagement, $storeManager, $resultJsonFactory, $dataObjectFactory, $jsonHelper, $escaper);
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
