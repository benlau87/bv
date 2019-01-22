<?php
namespace Benlau\SkontoDiscount\Controller\Checkout\ApplyPaymentMethod;

/**
 * Interceptor class for @see \Benlau\SkontoDiscount\Controller\Checkout\ApplyPaymentMethod
 */
class Interceptor extends \Benlau\SkontoDiscount\Controller\Checkout\ApplyPaymentMethod implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Action\Context $context, \Magento\Framework\Controller\Result\ForwardFactory $resultForwardFactory, \Magento\Framework\View\LayoutFactory $layoutFactory, \Magento\Checkout\Model\Cart $cart)
    {
        $this->___init();
        parent::__construct($context, $resultForwardFactory, $layoutFactory, $cart);
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
