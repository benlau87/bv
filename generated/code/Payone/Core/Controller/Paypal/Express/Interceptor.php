<?php
namespace Payone\Core\Controller\Paypal\Express;

/**
 * Interceptor class for @see \Payone\Core\Controller\Paypal\Express
 */
class Interceptor extends \Payone\Core\Controller\Paypal\Express implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Action\Context $context, \Magento\Checkout\Model\Session $checkoutSession, \Payone\Core\Model\Api\Request\Genericpayment\PayPalExpress $genericRequest, \Payone\Core\Model\Methods\Paypal $paypalPayment, \Magento\Checkout\Helper\Data $checkoutHelper, \Magento\Customer\Model\Session $customerSession, \Payone\Core\Helper\Payment $paymentHelper)
    {
        $this->___init();
        parent::__construct($context, $checkoutSession, $genericRequest, $paypalPayment, $checkoutHelper, $customerSession, $paymentHelper);
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
