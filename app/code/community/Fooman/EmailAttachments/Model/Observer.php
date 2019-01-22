<?php

/**
 * @author     Kristof Ringleff
 * @package    Fooman_EmailAttachments
 * @copyright  Copyright (c) 2009 Fooman Limited (http://www.fooman.co.nz)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

class Fooman_EmailAttachments_Model_Observer
{

    const XML_PATH_ORDER_PACKINGSLIP_TEMPLATE = 'sales_email/order/shipment_template';
    const XML_PATH_EMAIL_COPY_TO = 'sales_email/order/shipment_to';

    const KEY_PACKING_SLIP_PROCESSED ='emailattachments-packingslip-processed';

    /**
     * observe core_block_abstract_prepare_layout_after to add a Print Orders
     * massaction to the actions dropdown menu
     *
     * @param $observer
     */
    public function addbutton($observer)
    {
        $block = $observer->getEvent()->getBlock();
        //add button to dropdown
        if ($block instanceof Mage_Adminhtml_Block_Widget_Grid_Massaction
            || $block instanceof
                Enterprise_SalesArchive_Block_Adminhtml_Sales_Order_Grid_Massaction
        ) {
            if ($block->getRequest()->getControllerName() == 'sales_order'
                || $block->getRequest()->getControllerName() == 'adminhtml_sales_order'
                || $block->getRequest()->getControllerName() == 'sales_archive'
            ) {
                $block->addItem(
                    'pdforders_order', array(
                        'label'=> Mage::helper('emailattachments')->__('Print Orders'),
                        'url'  => Mage::helper('adminhtml')->getUrl(
                            'emailattachments/admin_order/pdforders',
                            Mage::app()->getStore()->isCurrentlySecure() ? array('_secure'=> 1) : array()
                        ),
                    )
                );
            }
        }
        //add button to single order view
        if ($block instanceof Mage_Adminhtml_Block_Sales_Order_View) {
            Mage::helper('emailattachments')->addButton($block);
        }
    }

    /**
     * listen to order email send event to attach pdfs and agreements
     *
     * @param $observer
     */
    public function beforeSendOrder ($observer)
    {
        $update = $observer->getEvent()->getUpdate();
        $mailTemplate = $observer->getEvent()->getTemplate();
        $order = $observer->getEvent()->getObject();
        $configPath = $update ? 'order_comment' : 'order';

        if (Mage::getStoreConfig('sales_email/' . $configPath . '/attachpdf', $order->getStoreId())) {
            //Create Pdf and attach to email - play nicely with PdfCustomiser
            $appEmulation = Mage::getSingleton('core/app_emulation');
            $initial = $appEmulation->startEnvironmentEmulation(
                Mage_Core_Model_App::ADMIN_STORE_ID, Mage_Core_Model_App_Area::AREA_ADMINHTML, true
            );
            $pdf = Mage::getModel('emailattachments/order_pdf_order')->getPdf(array($order));
            $appEmulation->stopEnvironmentEmulation($initial);
            $mailTemplate = Mage::helper('emailattachments')->addAttachment(
                $pdf, $mailTemplate, Mage::helper('sales')->__('Order') . "_" . $order->getIncrementId()
            );
        }

        if (Mage::getStoreConfig('sales_email/' . $configPath . '/attachagreement', $order->getStoreId())) {
            $mailTemplate = Mage::helper('emailattachments')->addAgreements($order->getStoreId(), $mailTemplate);
        }

        $fileAttachment = Mage::getStoreConfig('sales_email/' . $configPath . '/attachfile', $order->getStoreId());
        if ($fileAttachment) {
            $mailTemplate = Mage::helper('emailattachments')->addFileAttachment($fileAttachment, $mailTemplate);
        }
    }

    /**
     * listen to order email send event to send packing slip
     *
     * @param $observer
     */
    public function sendPackingSlip ($observer)
    {
        if (!Mage::registry(self::KEY_PACKING_SLIP_PROCESSED)) {
            Mage::register(self::KEY_PACKING_SLIP_PROCESSED, true);
        } else {
            //only process this once
            return;
        }
        $update = $observer->getEvent()->getUpdate();
        $mailTemplate = Mage::getModel('core/email_template');
        $order = $observer->getEvent()->getObject();
        $configPath = $update ? 'order_comment' : 'order';
        $emails = Mage::helper('emailattachments')->getEmails(self::XML_PATH_EMAIL_COPY_TO, $order->getStoreId());

        if ($emails && Mage::getStoreConfig('sales_email/' . $configPath . '/sendpackingslip', $order->getStoreId())) {
            $template = Mage::getStoreConfig(self::XML_PATH_ORDER_PACKINGSLIP_TEMPLATE, $order->getStoreId());
            $pdf = Mage::getModel('sales/order_pdf_shipment')->getPdf(array(),array($order->getId()));
            $mailTemplate = Mage::helper('emailattachments')->addAttachment(
                $pdf, $mailTemplate, Mage::helper('sales')->__('Shipment') . "_" . $order->getIncrementId()
            );
            foreach ($emails as $email) {
                $mailTemplate->setDesignConfig(array('area' => 'frontend', 'store' => $order->getStoreId()))
                    ->sendTransactional(
                        $template,
                        Mage::getStoreConfig(
                            Mage_Sales_Model_Order_Shipment::XML_PATH_EMAIL_IDENTITY, $order->getStoreId()
                        ),
                        $email,
                        '',
                        array(
                             'order' => $order
                        )
                    );
            }
        }


    }

    /**
     * listen to invoice email send event to attach pdfs and agreements
     *
     * @param $observer
     */
    public function beforeSendInvoice ($observer)
    {
        $update = $observer->getEvent()->getUpdate();
        $mailTemplate = $observer->getEvent()->getTemplate();
        $invoice = $observer->getEvent()->getObject();
        $configPath = $update ? 'invoice_comment' : 'invoice';

        if (Mage::getStoreConfig('sales_email/' . $configPath . '/attachpdf', $invoice->getStoreId())) {
            //Create Pdf and attach to email - play nicely with PdfCustomiser
            $pdf = Mage::getModel('sales/order_pdf_invoice')->getPdf(array($invoice));
            $mailTemplate = Mage::helper('emailattachments')->addAttachment(
                $pdf,
                $mailTemplate,
                Mage::helper('sales')->__('Invoice') . "_" . $invoice->getIncrementId()
            );
        }

        if (Mage::getStoreConfig('sales_email/' . $configPath . '/attachagreement', $invoice->getStoreId())) {
            $mailTemplate = Mage::helper('emailattachments')->addAgreements($invoice->getStoreId(), $mailTemplate);
        }

        $fileAttachment = Mage::getStoreConfig('sales_email/' . $configPath . '/attachfile', $invoice->getStoreId());
        if ($fileAttachment) {
            $mailTemplate = Mage::helper('emailattachments')->addFileAttachment($fileAttachment, $mailTemplate);
        }
    }

    /**
     * listen to shipment email send event to attach pdfs and agreements
     *
     * @param $observer
     */
    public function beforeSendShipment ($observer)
    {
        $update = $observer->getEvent()->getUpdate();
        $mailTemplate = $observer->getEvent()->getTemplate();
        $shipment = $observer->getEvent()->getObject();
        $configPath = $update ? 'shipment_comment' : 'shipment';

        if (Mage::getStoreConfig('sales_email/' . $configPath . '/attachpdf', $shipment->getStoreId())) {
            //Create Pdf and attach to email - play nicely with PdfCustomiser
            $pdf = Mage::getModel('sales/order_pdf_shipment')->getPdf(array($shipment));
            $mailTemplate = Mage::helper('emailattachments')->addAttachment(
                $pdf, $mailTemplate, Mage::helper('sales')->__('Shipment') . "_" . $shipment->getIncrementId()
            );
            /*
                $pdf = Mage::getModel('sales/order_pdf_shipment')->getPdf($shipment->getOrder()->getInvoiceCollection());
                $mailTemplate = Mage::helper('emailattachments')->addAttachment(
                    $pdf, $mailTemplate, Mage::helper('sales')->__('Invoices for Order') . "_" . $shipment->getOrder()->getIncrementId()
                );
            */
        }

        if (Mage::getStoreConfig('sales_email/' . $configPath . '/attachagreement', $shipment->getStoreId())) {
            $mailTemplate = Mage::helper('emailattachments')->addAgreements($shipment->getStoreId(), $mailTemplate);
        }

        $fileAttachment = Mage::getStoreConfig('sales_email/' . $configPath . '/attachfile', $shipment->getStoreId());
        if ($fileAttachment) {
            $mailTemplate = Mage::helper('emailattachments')->addFileAttachment($fileAttachment, $mailTemplate);
        }
    }

    /**
     * listen to creditmemo email send event to attach pdfs and agreements
     *
     * @param $observer
     */
    public function beforeSendCreditmemo ($observer)
    {
        $update = $observer->getEvent()->getUpdate();
        $mailTemplate = $observer->getEvent()->getTemplate();
        $creditmemo = $observer->getEvent()->getObject();
        $configPath = $update ? 'creditmemo_comment' : 'creditmemo';

        if (Mage::getStoreConfig('sales_email/' . $configPath . '/attachpdf', $creditmemo->getStoreId())) {
            //Create Pdf and attach to email - play nicely with PdfCustomiser
            $pdf = Mage::getModel('sales/order_pdf_creditmemo')->getPdf(array($creditmemo));
            $mailTemplate = Mage::helper('emailattachments')->addAttachment(
                $pdf, $mailTemplate, Mage::helper('sales')->__('Credit Memo') . "_" . $creditmemo->getIncrementId()
            );
        }

        if (Mage::getStoreConfig('sales_email/' . $configPath . '/attachagreement', $creditmemo->getStoreId())) {
            $mailTemplate = Mage::helper('emailattachments')->addAgreements($creditmemo->getStoreId(), $mailTemplate);
        }

        $fileAttachment = Mage::getStoreConfig('sales_email/' . $configPath . '/attachfile', $creditmemo->getStoreId());
        if ($fileAttachment) {
            $mailTemplate = Mage::helper('emailattachments')->addFileAttachment($fileAttachment, $mailTemplate);
        }
    }

}