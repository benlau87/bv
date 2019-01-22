<?php
namespace Iways\PayPalPlus\Controller\Order\Create;

/**
 * Interceptor class for @see \Iways\PayPalPlus\Controller\Order\Create
 */
class Interceptor extends \Iways\PayPalPlus\Controller\Order\Create implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Action\Context $context, \Psr\Log\LoggerInterface $logger, \Magento\Checkout\Model\Session $checkoutSession, \Magento\Checkout\Helper\Data $checkoutHelper, \Magento\Quote\Api\CartManagementInterface $cartManagement, \Magento\Quote\Api\GuestCartManagementInterface $guestCartManagement, \Magento\Quote\Model\QuoteIdMaskFactory $quoteIdMaskFactory, \Magento\Sales\Model\Order\Email\Sender\OrderSender $orderSender, \Magento\Sales\Model\OrderFactory $orderFactory, \Magento\Sales\Model\Order\Status\HistoryFactory $historyFactory, \Magento\Customer\Model\Session $customerSession)
    {
        $this->___init();
        parent::__construct($context, $logger, $checkoutSession, $checkoutHelper, $cartManagement, $guestCartManagement, $quoteIdMaskFactory, $orderSender, $orderFactory, $historyFactory, $customerSession);
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
