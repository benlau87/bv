<?php
namespace Payone\Core\Controller\Paypal\Returned;

/**
 * Interceptor class for @see \Payone\Core\Controller\Paypal\Returned
 */
class Interceptor extends \Payone\Core\Controller\Paypal\Returned implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Action\Context $context, \Magento\Checkout\Model\Session $checkoutSession, \Payone\Core\Model\Methods\Paypal $paypalPayment, \Payone\Core\Model\Paypal\ReturnHandler $returnHandler)
    {
        $this->___init();
        parent::__construct($context, $checkoutSession, $paypalPayment, $returnHandler);
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
