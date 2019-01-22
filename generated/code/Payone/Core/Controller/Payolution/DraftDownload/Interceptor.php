<?php
namespace Payone\Core\Controller\Payolution\DraftDownload;

/**
 * Interceptor class for @see \Payone\Core\Controller\Payolution\DraftDownload
 */
class Interceptor extends \Payone\Core\Controller\Payolution\DraftDownload implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Action\Context $context, \Magento\Checkout\Model\Session $checkoutSession, \Payone\Core\Helper\Payment $paymentHelper, \Magento\Framework\Controller\Result\RawFactory $resultRawFactory, \Magento\Framework\HTTP\Client\Curl $curl)
    {
        $this->___init();
        parent::__construct($context, $checkoutSession, $paymentHelper, $resultRawFactory, $curl);
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
