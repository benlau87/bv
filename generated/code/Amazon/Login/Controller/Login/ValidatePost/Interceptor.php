<?php
namespace Amazon\Login\Controller\Login\ValidatePost;

/**
 * Interceptor class for @see \Amazon\Login\Controller\Login\ValidatePost
 */
class Interceptor extends \Amazon\Login\Controller\Login\ValidatePost implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Action\Context $context, \Amazon\Login\Helper\Session $session, \Amazon\Login\Model\Customer\Account\Redirect $accountRedirect, \Magento\Customer\Model\CustomerRegistry $customerRegistry, \Magento\Framework\Encryption\Encryptor $encryptor, \Amazon\Login\Api\CustomerManagerInterface $customerManager)
    {
        $this->___init();
        parent::__construct($context, $session, $accountRedirect, $customerRegistry, $encryptor, $customerManager);
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
