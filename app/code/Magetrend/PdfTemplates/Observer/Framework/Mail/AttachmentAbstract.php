<?php
/**
 * MB "Vienas bitas" (Magetrend.com)
 *
 * @category MageTrend
 * @package  Magetend/PdfTemplates
 * @author   Edvinas Stulpinas <edwin@magetrend.com>
 * @license  http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link     https://www.magetrend.com/magento-2-pdf-invoice-pro
 */

namespace Magetrend\PdfTemplates\Observer\Framework\Mail;

use Magento\Framework\Event\Observer;
use Magento\Store\Model\ScopeInterface;

/**
 * TransportInterfaceFactory Plugin class
 *
 * @category MageTrend
 * @package  Magetend/PdfTemplates
 * @author   Edvinas Stulpinas <edwin@magetrend.com>
 * @license  http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link     https://www.magetrend.com/magento-2-pdf-invoice-pro
 */
abstract class AttachmentAbstract implements \Magento\Framework\Event\ObserverInterface
{
    public $moduleHelper;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    public $scopeConfig;

    public $pdfTemplate;

    public $orderRepository;

    public $invoiceRepository;

    public $shipmentRepository;

    public $creditmemoRepository;

    public function __construct(
        \Magetrend\PdfTemplates\Helper\Data $moduleHelper,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magetrend\PdfTemplates\Model\Template $pdfTemplate,
        \Magento\Sales\Api\OrderRepositoryInterface $orderRepository,
        \Magento\Sales\Api\InvoiceRepositoryInterface $invoiceRepository,
        \Magento\Sales\Api\ShipmentRepositoryInterface $shipmentRepository,
        \Magento\Sales\Api\CreditmemoRepositoryInterface $creditmemoRepository
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->moduleHelper = $moduleHelper;
        $this->pdfTemplate = $pdfTemplate;
        $this->orderRepository = $orderRepository;
        $this->invoiceRepository = $invoiceRepository;
        $this->shipmentRepository = $shipmentRepository;
        $this->creditmemoRepository = $creditmemoRepository;
    }

    abstract public function execute(Observer $observer);

    public function getStoreId($observer)
    {
        $templateVars = $observer->getTemplateVars();
        if (!isset($templateVars['store'])) {
            return 0;
        }

        $store = $templateVars['store'];
        return $store->getId();
    }

    public function needToAttach($type, $storeId = 0)
    {
        return $this->scopeConfig->getValue(
            'pdftemplates/attachments/'.$type,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    public function attachFile($observer, $pdfString, $fileName)
    {
        if (empty($pdfString)) {
            return false;
        }
        /**
         * @var \Magento\Framework\Mail\Message $message
         */
        $message = $observer->getMessage();
        $body = $message->getBody();
        if (method_exists($message, 'createAttachment')) {
            /**
             * before Magento 2.3
             */
            $message->createAttachment(
                $pdfString,
                'application/pdf',
                \Zend_Mime::DISPOSITION_ATTACHMENT,
                \Zend_Mime::ENCODING_BASE64,
                $fileName
            );
        } elseif ($body instanceof \Zend\Mime\Message) {
            $attachment = new \Zend\Mime\Part($pdfString);
            $attachment->type = 'application/pdf';
            $attachment->filename = $fileName;
            $attachment->disposition = \Zend\Mime\Mime::DISPOSITION_ATTACHMENT;
            $attachment->encoding = \Zend\Mime\Mime::ENCODING_BASE64;
            $body->addPart($attachment);
            $message->setBody($body);
        } else {
            return false;
        }

        return false;
    }

    public function getPdf($objects)
    {
        if (empty($objects)) {
            return '';
        }

        $pdf = $this->pdfTemplate->getPdf($objects);
        if (!$pdf) {
            return '';
        }

        return $pdf->render();
    }

    public function getOrder($observer)
    {
        $templateVars = $observer->getTemplateVars();
        if (!isset($templateVars['order'])) {
            return '';
        }

        $orderVar = $templateVars['order'];
        $orderId = $orderVar->getId();

        $order = $this->orderRepository->get($orderId);
        return $order;
    }

    public function getInvoice($observer)
    {
        $templateVars = $observer->getTemplateVars();
        if (!isset($templateVars['invoice'])) {
            return '';
        }

        $invoiceVariable = $templateVars['invoice'];
        $invoiceId = $invoiceVariable->getId();

        $invoice = $this->invoiceRepository->get($invoiceId);
        return $invoice;
    }

    public function getShipment($observer)
    {
        $templateVars = $observer->getTemplateVars();
        if (!isset($templateVars['shipment'])) {
            return '';
        }

        $shipmentVariable = $templateVars['shipment'];
        $shipmentId = $shipmentVariable->getId();

        $shipment = $this->shipmentRepository->get($shipmentId);
        return $shipment;
    }

    public function getCreditmemo($observer)
    {
        $templateVars = $observer->getTemplateVars();
        if (!isset($templateVars['creditmemo'])) {
            return '';
        }

        $creditmemoVariable = $templateVars['creditmemo'];
        $creditmemoId = $creditmemoVariable->getId();

        $creditmemo = $this->creditmemoRepository->get($creditmemoId);
        return $creditmemo;
    }

    public function validateTemplate($observer, $configPaths = [])
    {
        $templateId = $observer->getTemplateId();
        $storeId = $this->getStoreId($observer);

        foreach ($configPaths as $xmlPath) {
            $configValue = $this->scopeConfig->getValue(
                $xmlPath,
                ScopeInterface::SCOPE_STORE,
                $storeId
            );

            if ($configValue == $templateId) {
                return true;
            }
        }

        return false;
    }
}
