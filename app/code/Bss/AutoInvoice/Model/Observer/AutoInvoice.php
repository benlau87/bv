<?php
/**
 * BSS Commerce Co.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at thisURL:
 * http://bsscommerce.com/Bss-Commerce-License.txt
 *
 * @category   BSS
 * @package    Bss_AutoInvoice
 * @author     Extension Team
 * @copyright  Copyright (c) 2017-2018 BSS Commerce Co. ( http://bsscommerce.com )
 * @license    http://bsscommerce.com/Bss-Commerce-License.txt
 */
namespace Bss\AutoInvoice\Model\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Sales\Model\Order;
use Magento\Sales\Model\Order\Email\Sender\InvoiceSender;
use Magento\Sales\Model\Order\Email\Sender\ShipmentSender;
use Magento\Sales\Model\Service\InvoiceService;
use Magento\Sales\Model\Order\ShipmentDocumentFactory;
use Magento\Sales\Model\Order\ShipmentFactory;
use \Magento\Framework\Exception\LocalizedException;
use \Psr\Log\LoggerInterface;

class AutoInvoice implements ObserverInterface
{

    /**
     * Helper Data
     *
     * @var \Bss\AutoInvoice\Helper\Data $helper
     */
    protected $helper;

    /**
     * InvoiceSender
     *
     * @var InvoiceSender $invoiceSender
     */
    protected $invoiceSender;

    /**
     * ShipmentSender
     *
     * @var ShipmentSender $shipmentSender
     */
    protected $shipmentSender;

    /**
     * ShipmentFactory
     *
     * @var ShipmentFactory $shipmentFactory
     */
    protected $shipmentFactory;

    /**
     * ShipmentDocumentFactory
     *
     * @var ShipmentDocumentFactory $shipmentDocumentFactory
     */
    protected $shipmentDocumentFactory;

    /**
     * InvoiceService
     *
     * @var InvoiceService $invoiceService
     */
    private $invoiceService;

    /**
     * ProductMetadataInterface
     *
     * @var \Magento\Framework\App\ProductMetadataInterface $productMetadata
     */
    private $productMetadata;

    /**
     * Transaction
     *
     * @var \Magento\Framework\DB\Transaction $transaction
     */
    private $transaction;

    /**
     * Logger
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * AutoInvoice constructor.
     *
     * @param \Bss\AutoInvoice\Helper\Data $helper
     * @param InvoiceSender $invoiceSender
     * @param ShipmentSender $shipmentSender
     * @param ShipmentDocumentFactory $shipmentDocumentFactory
     * @param ShipmentFactory $shipmentFactory
     * @param InvoiceService $invoiceService
     * @param \Magento\Framework\App\ProductMetadataInterface $productMetadata
     * @param \Magento\Framework\DB\TransactionFactory $transaction
     * @param LoggerInterface $logger
     */
    public function __construct(
        \Bss\AutoInvoice\Helper\Data $helper,
        InvoiceSender $invoiceSender,
        ShipmentSender $shipmentSender,
        ShipmentDocumentFactory $shipmentDocumentFactory,
        ShipmentFactory $shipmentFactory,
        InvoiceService $invoiceService,
        \Magento\Framework\App\ProductMetadataInterface $productMetadata,
        \Magento\Framework\DB\TransactionFactory $transaction,
        LoggerInterface $logger
    ) {
        $this->helper = $helper;
        $this->invoiceSender = $invoiceSender;
        $this->shipmentSender = $shipmentSender;
        $this->invoiceService = $invoiceService;
        $this->shipmentFactory = $shipmentFactory;
        $this->shipmentDocumentFactory = $shipmentDocumentFactory;
        $this->productMetadata = $productMetadata;
        $this->transaction = $transaction;
        $this->logger = $logger;
    }

    /**
     * Execute
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        if ($this->helper->isEnabled()) {
            $order = $observer->getEvent()->getOrder()->save();
            if (!$order->getId()) {
                throw new \Magento\Framework\Exception\LocalizedException(__('The order no longer exists.'));
            }
            $payment = $order->getPayment()->getMethodInstance()->getCode();
            $shipmentFact = $this->getShipmentByVersion();

            if ($this->helper->checkPaymentmethod($payment) && $this->checkStateOrder($order)
            ) {
                try {
                    $this->checkOrder($order);

                    $invoice = $this->invoiceService->prepareInvoice($order);
                    $this->checkInvoice($invoice);
                    $invoice->setRequestedCaptureCase(\Magento\Sales\Model\Order\Invoice::CAPTURE_OFFLINE);
                    $invoice->register();
                    $invoice->getOrder()->setCustomerNoteNotify(false);
                    $invoice->getOrder()->setIsInProcess(true);
                    $transactionSave = $this->transaction->create()->addObject(
                        $invoice
                    )->addObject(
                        $invoice->getOrder()
                    );
                    $transactionSave->save();
                    // send invoice email
                    try {
                        $this->invoiceSender->send($invoice);
                    } catch (\Exception $e) {
                        $this->logger->error($e->getMessage());
                    }
                    $shipment = false;
                    if ($this->helper->enabledShipment() && $order->canShip()) {
                        $shipment = $shipmentFact->create(
                            $invoice->getOrder()
                        );
                        $shipment->register();
                        if ($shipment) {
                            $transactionSave->addObject($shipment);
                        }
                        $transactionSave->save();
                        $this->sendShipment($shipment);
                    }
                    $order->addStatusHistoryComment(
                        $this->getStatusHistoryComment($shipment),
                        false
                    )->save();
                } catch (\Exception $e) {
                    $this->logger->error($e->getMessage());
                }
            }
        }
    }

    /**
     * Send Shipment
     *
     * @param $shipment
     */
    protected function sendShipment($shipment)
    {
        if ($shipment) {
            try {
                $this->shipmentSender->send($shipment);
            } catch (\Exception $e) {
                $this->logger->error($e->getMessage());
            }
        }
    }

    /**
     * Check Order
     * @param $order
     * @throws LocalizedException
     */
    protected function checkOrder($order)
    {
        if (!$order->canInvoice()
        ) {
            throw new \Magento\Framework\Exception\LocalizedException(
                __('The order does not allow an invoice to be created.')
            );
        }
    }

    /**
     * Check Invoice
     * @param $invoice
     * @throws LocalizedException
     */
    protected function checkInvoice($invoice)
    {
        if (!$invoice) {
            throw new LocalizedException(__('We can\'t save the invoice right now.'));
        }

        if (!$invoice->getTotalQty()) {
            throw new \Magento\Framework\Exception\LocalizedException(
                __('You can\'t create an invoice without products.')
            );
        }
    }

    /**
     * Get Status History Comment
     *
     * @param $shipment
     * @return string
     */
    protected function getStatusHistoryComment($shipment)
    {
        if ($shipment) {
            return 'Automatically Invoiced and Shipment by Bss AutoInvoice.';
        } else {
            return 'Automatically Invoiced by Bss AutoInvoice.';
        }
    }

    /**
     * Get Shipment By Version
     *
     * @return ShipmentDocumentFactory|ShipmentFactory
     */
    protected function getShipmentByVersion()
    {
        if ($this->getMagentoVersion()) {
            return $this->shipmentFactory;
        } else {
            return $this->shipmentDocumentFactory;
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

    /**
     * Get Magento Version
     *
     * @return bool
     */
    private function getMagentoVersion()
    {
        $dataVersion = $this->productMetadata->getVersion();
        if (version_compare($dataVersion, '2.2.0') >= 0) {
            return false;
        }
        return true;
    }
}
