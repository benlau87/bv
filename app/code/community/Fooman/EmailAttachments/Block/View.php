<?php
class Fooman_EmailAttachments_Block_View extends Mage_Adminhtml_Block_Sales_Order_View {

    public function __construct() {
        parent::__construct();
        $this->_addButton('print', array(
            'label'     => Mage::helper('sales')->__('Print'),
            'class'     => 'save',
            'onclick'   => 'setLocation(\''.$this->getPrintUrl().'\')'
            )
        );
    }

    public function getPrintUrl() {
        return $this->getUrl('emailattachments/admin_order/print', array(
        'order_id' => $this->getOrder()->getId()
        ));
    }
}