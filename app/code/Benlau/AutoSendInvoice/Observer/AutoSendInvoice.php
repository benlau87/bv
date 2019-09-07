<?php
namespace Benlau\AutoSendInvoice\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Sales\Model\Order;
use Magento\Sales\Model\Order\Email\Sender\InvoiceSender;
use \Magento\Framework\Exception\LocalizedException;
use \Psr\Log\LoggerInterface;

class AutoSendInvoice implements ObserverInterface
{
    /**
     * @var \Magento\Sales\Model\OrderFactory
     */
    protected $orderModel;

    /**
     * @var \Magento\Sales\Model\Order\Email\Sender\InvoiceSender
     */
    protected $invoiceSender;

    /**
     * Logger
     * @var LoggerInterface
     */
    protected $logger;


    /**
     * @param \Magento\Sales\Model\OrderFactory $orderModel
     * @param InvoiceSender $invoiceSender
     * @param LoggerInterface $logger
     */
    public function __construct(
        \Magento\Sales\Model\OrderFactory $orderModel,
        \Magento\Sales\Model\Order\Email\Sender\InvoiceSender $invoiceSender,
        LoggerInterface $logger
    )
    {
        $this->orderModel = $orderModel;
        $this->invoiceSender = $invoiceSender;
        $this->logger = $logger;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        // get the corresponding order & invoice
        $invoice = $observer->getEvent()->getInvoice();
        $order = $invoice->getOrder();

        $debug = false;

        if (!$order->getId()) {
            throw new LocalizedException(__('The order no longer exists.'));
        }

        // send invoice email only for PayPal Plus and Amazon Pay AND if order status is "new" or "processing"
        if ( $this->checkPaymentMethod($order )
            && $this->checkStateOrder($order )
            && !$invoice->getEmailSent()
            ) {
            try {
                // check if order is allowed to create invoice
                #$this->checkOrder($order);

                // send invoice email
                try {
                    $this->invoiceSender->send($invoice);

                    if($debug)
                        mail('info@benlau.de', __FUNCTION__, 'invoice mail sent for invoice #' . $invoice->getId());
                } catch (\Exception $e) {
                    $this->logger->error($e->getMessage());
                }

                // send order mail
/*                if ( !$order->getEmailSent() )
                    $order->orderSender->send($order);*/

                // add order comment
                $order->addStatusHistoryComment(
                    'Kunde hat Rechnung automatisch per E-Mail erhalten - Benlau_AutoSendInvoice',
                    false
                )->save();


            } catch (\Exception $e) {
                $this->logger->error($e->getMessage());
            }
        }

        if($debug)
            mail('info@benlau.de', __FUNCTION__, 'end');
    }


    /**
     * @param $order
     * @throws LocalizedException
     */
    protected function checkOrder($order)
    {
        if (!$order->canInvoice()
        ) {
            throw new LocalizedException(
                __('The order does not allow an invoice to be created.')
            );
        }
    }

    protected function checkPaymentMethod($order) {
        // get the payment method for corresponding order
        $payment = $order->getPayment()->getMethodInstance()->getCode();

        if ( $payment == 'iways_paypalplus_payment' || $payment == 'amazon_payment' ) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Check State Order
     *
     * @param $order
     * @return bool
     */
    protected function checkStateOrder($order)
    {
        if ($order->getState() == Order::STATE_NEW || $order->getState() == Order::STATE_PROCESSING) {
            return true;
        } else {
            return false;
        }
    }
}