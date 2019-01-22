<?php
namespace Amazon\Payment\Controller\Payment\Ipn;

/**
 * Interceptor class for @see \Amazon\Payment\Controller\Payment\Ipn
 */
class Interceptor extends \Amazon\Payment\Controller\Payment\Ipn implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Action\Context $context, \Amazon\Payment\Ipn\IpnHandlerFactoryInterface $ipnHandlerFactory, \Amazon\Payment\Api\Ipn\CompositeProcessorInterface $compositeProcessor, \Amazon\Core\Helper\Data $coreHelper)
    {
        $this->___init();
        parent::__construct($context, $ipnHandlerFactory, $compositeProcessor, $coreHelper);
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
