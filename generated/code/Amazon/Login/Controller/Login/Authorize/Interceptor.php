<?php
namespace Amazon\Login\Controller\Login\Authorize;

/**
 * Interceptor class for @see \Amazon\Login\Controller\Login\Authorize
 */
class Interceptor extends \Amazon\Login\Controller\Login\Authorize implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Action\Context $context, \Amazon\Core\Client\ClientFactoryInterface $clientFactory, \Amazon\Login\Api\Customer\CompositeMatcherInterface $matcher, \Amazon\Login\Api\CustomerManagerInterface $customerManager, \Amazon\Login\Helper\Session $session, \Amazon\Login\Model\Customer\Account\Redirect $accountRedirect, \Amazon\Core\Domain\AmazonCustomerFactory $amazonCustomerFactory, \Psr\Log\LoggerInterface $logger, \Amazon\Core\Helper\Data $amazonCoreHelper, \Magento\Customer\Model\Url $customerUrl, \Amazon\Login\Model\Validator\AccessTokenRequestValidator $accessTokenRequestValidator)
    {
        $this->___init();
        parent::__construct($context, $clientFactory, $matcher, $customerManager, $session, $accountRedirect, $amazonCustomerFactory, $logger, $amazonCoreHelper, $customerUrl, $accessTokenRequestValidator);
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
