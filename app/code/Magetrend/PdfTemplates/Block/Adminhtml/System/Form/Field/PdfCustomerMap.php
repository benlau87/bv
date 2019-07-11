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

namespace Magetrend\PdfTemplates\Block\Adminhtml\System\Form\Field;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Sales\Model\ResourceModel\Order\Invoice\Collection;

/**
 * PDF - Customer Field class
 *
 * @category MageTrend
 * @package  Magetend/PdfTemplates
 * @author   Edvinas Stulpinas <edwin@magetrend.com>
 * @license  http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link     https://www.magetrend.com/magento-2-pdf-invoice-pro
 */
class PdfCustomerMap extends \Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray
{
    public $customerGroup;

    public $invoiceRenderer;

    public $creditmemoRenderer;

    public $shipmentRenderer;

    /**
     * Get activation options.
     *
     * @return \Magently\Tutorial\Block\Adminhtml\Form\Field\Activation
     */
    public function getCustomerGroupRenderer()
    {
        if (!$this->customerGroup) {
            $this->customerGroup = $this->getLayout()->createBlock(
                '\Magetrend\PdfTemplates\Block\Adminhtml\System\Form\Field\CustomerGroup',
                '',
                ['data' => ['is_render_to_js_template' => true]]
            );
        }

        return $this->customerGroup;
    }
    
    public function getInvoicePDFTemplateRenderer()
    {
        if (!$this->invoiceRenderer) {
            $this->invoiceRenderer = $this->getLayout()->createBlock(
                '\Magetrend\PdfTemplates\Block\Adminhtml\System\Form\Field\InvoicePdfTemplate',
                '',
                ['data' => [ 'is_render_to_js_template' => true]]
            );
        }

        return $this->invoiceRenderer;
    }

    public function getCreditmemoPDFTemplateRenderer()
    {
        if (!$this->creditmemoRenderer) {
            $this->creditmemoRenderer = $this->getLayout()->createBlock(
                '\Magetrend\PdfTemplates\Block\Adminhtml\System\Form\Field\CreditmemoPdfTemplate',
                '',
                ['data' => [ 'is_render_to_js_template' => true]]
            );
        }

        return $this->creditmemoRenderer;
    }


    public function getShipmentPDFTemplateRenderer()
    {
        if (!$this->shipmentRenderer) {
            $this->shipmentRenderer = $this->getLayout()->createBlock(
                '\Magetrend\PdfTemplates\Block\Adminhtml\System\Form\Field\ShipmentPdfTemplate',
                '',
                ['data' => [ 'is_render_to_js_template' => true]]
            );
        }

        return $this->shipmentRenderer;
    }

    /**
     * Prepare to render.
     *
     * @return void
     */
    protected function _prepareToRender()
    {
        $this->addColumn(
            'customer_group',
            [
                'label' => __('Customer Group'),
                'renderer' => $this->getCustomerGroupRenderer()
            ]
        );

        $field = $this->getelement()->getFieldConfig();
        switch ($field['id']) {
            case 'invoice_template_customer_group':
                $renderer = $this->getInvoicePDFTemplateRenderer();
                break;
            case 'creditmemo_template_customer_group':
                $renderer = $this->getCreditmemoPDFTemplateRenderer();
                break;
            case 'shipment_template_customer_group':
                $renderer = $this->getShipmentPDFTemplateRenderer();
                break;
        }

        $this->addColumn(
            $field['id'].'_'.'template',
            [
                'label' => __('PDF Template'),
                'renderer' => $renderer
            ]
        );

        $this->_addAfter = false;
        $this->_addButtonLabel = __('Add');
    }

    /**
     * Prepare existing row data object.
     *
     * @param \Magento\Framework\DataObject $row
     * @return void
     */
    protected function _prepareArrayRow(\Magento\Framework\DataObject $row)
    {
        $options = [];
        $customAttribute = $row->getData('customer_group');

        $key = 'option_' . $this->getCustomerGroupRenderer()->calcOptionHash($customAttribute);
        $options[$key] = 'selected="selected"';
        $row->setData('option_extra_attrs', $options);
    }
}