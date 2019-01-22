<?php
namespace Payone\Core\Controller\Onepage\PlaceOrder;

/**
 * Interceptor class for @see \Payone\Core\Controller\Onepage\PlaceOrder
 */
class Interceptor extends \Payone\Core\Controller\Onepage\PlaceOrder implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Action\Context $context, \Magento\Checkout\Api\AgreementsValidatorInterface $agreementValidator, \Magento\Checkout\Model\Session $checkoutSession, \Magento\Quote\Api\CartManagementInterface $cartManagement)
    {
        $this->___init();
        parent::__construct($context, $agreementValidator, $checkoutSession, $cartManagement);
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
