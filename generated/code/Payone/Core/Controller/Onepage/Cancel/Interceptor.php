<?php
namespace Payone\Core\Controller\Onepage\Cancel;

/**
 * Interceptor class for @see \Payone\Core\Controller\Onepage\Cancel
 */
class Interceptor extends \Payone\Core\Controller\Onepage\Cancel implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Action\Context $context, \Magento\Checkout\Model\Session $checkoutSession, \Magento\Sales\Model\OrderFactory $orderFactory, \Magento\Framework\Url $urlBuilder)
    {
        $this->___init();
        parent::__construct($context, $checkoutSession, $orderFactory, $urlBuilder);
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
