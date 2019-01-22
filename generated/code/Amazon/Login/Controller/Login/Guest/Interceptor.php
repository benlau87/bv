<?php
namespace Amazon\Login\Controller\Login\Guest;

/**
 * Interceptor class for @see \Amazon\Login\Controller\Login\Guest
 */
class Interceptor extends \Amazon\Login\Controller\Login\Guest implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Action\Context $context, \Amazon\Core\Domain\AmazonCustomerFactory $amazonCustomerFactory, \Amazon\Core\Client\ClientFactoryInterface $clientFactory, \Psr\Log\LoggerInterface $logger, \Magento\Customer\Model\Session $customerSession, \Amazon\Core\Helper\Data $amazonCoreHelper, \Magento\Customer\Model\Url $customerUrl, \Amazon\Login\Model\Validator\AccessTokenRequestValidator $accessTokenRequestValidator, \Amazon\Login\Model\Customer\Account\Redirect $accountRedirect)
    {
        $this->___init();
        parent::__construct($context, $amazonCustomerFactory, $clientFactory, $logger, $customerSession, $amazonCoreHelper, $customerUrl, $accessTokenRequestValidator, $accountRedirect);
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
