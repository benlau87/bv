<?php
namespace Payone\Core\Controller\Mandate\Download;

/**
 * Interceptor class for @see \Payone\Core\Controller\Mandate\Download
 */
class Interceptor extends \Payone\Core\Controller\Mandate\Download implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Action\Context $context, \Magento\Checkout\Model\Session $checkoutSession, \Payone\Core\Model\Api\Request\Getfile $getfileRequest, \Payone\Core\Helper\Payment $paymentHelper, \Magento\Framework\Controller\Result\RawFactory $resultRawFactory)
    {
        $this->___init();
        parent::__construct($context, $checkoutSession, $getfileRequest, $paymentHelper, $resultRawFactory);
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
