<?php

namespace Rokanthemes\OpCheckout\Block\Checkout;

class LayoutProcessor implements \Magento\Checkout\Block\Checkout\LayoutProcessorInterface
{
    
	protected $_helperConfig;
   
    public function __construct(
        \Rokanthemes\OpCheckout\Helper\Config $helperConfig
    ) {
        $this->_helperConfig = $helperConfig;
    }

    public function process($jsLayout)
    {
        if ($this->_helperConfig->isEnabledOneStep()) {
            if(isset($jsLayout['components']['checkout']['children']['steps']['children']['billing-step']['children']['payment']['children']['afterMethods']['children']['discount'])) {
                unset($jsLayout['components']['checkout']['children']['steps']['children']['billing-step']['children']['payment']['children']['afterMethods']['children']['discount']);
            }
            if(isset($jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']['shippingAddress']['children']['shipping-address-fieldset']['children'])) {
                $childs = $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']['shippingAddress']['children']['shipping-address-fieldset']['children'];

                $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']['shippingAddress']['children']['shipping-address-fieldset']['children'] = $this->processShippingInput($childs);
            }
            if(isset($jsLayout['components']['checkout']['children']['steps']['children']['billing-step']['children']['payment']['children']['payments-list']['children'])) {
                $childs = $jsLayout['components']['checkout']['children']['steps']['children']['billing-step']['children']['payment']['children']['payments-list']['children'];

                $jsLayout['components']['checkout']['children']['steps']['children']['billing-step']['children']['payment']['children']['payments-list']['children'] = $this->processBillingInput($childs);
            }
            if(isset($jsLayout['components']['checkout']['children']['steps']['children']['billing-step']['children']['payment']['children']['payments-list']['children']['before-place-order']['children']['agreements'])) {
                $jsLayout['components']['checkout']['children']['steps']['children']['billing-step']['children']['payment']['children']['payments-list']['children']['before-place-order']['children']['agreements']['config']['template'] = "Rokanthemes_OpCheckout/checkout/checkout-agreements";
            }
        }
        return $jsLayout;
    }

    public function processShippingInput($childs){
        if(count($childs) > 0){
            foreach($childs as $key => $child){
                if(isset($child['config']['template']) && $child['config']['template'] == 'ui/group/group' && isset($child['children'])){
                    $childs[$key]['component'] = "Rokanthemes_OpCheckout/js/view/form/components/group";
                    if (isset($childs[$key]['children'])) {
                        $children = $childs[$key]['children'];
                        $newChildren = array();
                        foreach ($children as $item) {
                            $item['config']['elementTmpl'] = "Rokanthemes_OpCheckout/form/element/input";
                            $newChildren[] = $item;
                        }
                        $childs[$key]['children'] = $newChildren;
                    }
                }
                if(isset($child['config']) && isset($child['config']['elementTmpl']) && $child['config']['elementTmpl'] == "ui/form/element/input"){
                    $childs[$key]['config']['elementTmpl'] = "Rokanthemes_OpCheckout/form/element/input";
                }
                if(isset($child['config']) && isset($child['config']['template']) && $child['config']['template'] == "ui/form/field"){
                    $childs[$key]['config']['template'] = "Rokanthemes_OpCheckout/js/form/components/field";
                    $childs[$key]['config']['template'] = "Rokanthemes_OpCheckout/form/field";
                }
                $sortOrder = $this->_helperConfig->getFieldSortOrder($key);
                if($sortOrder !== false){
                    $childs[$key]['sortOrder'] = strval($sortOrder);
                }
            }
        }
        return $childs;
    }

    public function processBillingInput($payments){
        if(count($payments) > 0){
            foreach($payments as $paymentCode => $paymentComponent){
                if (isset($paymentComponent['component']) && $paymentComponent['component'] != "Magento_Checkout/js/view/billing-address") {
                    continue;
                }
                $paymentComponent['component'] = "Rokanthemes_OpCheckout/js/view/billing-address";
                if(isset($paymentComponent['children']['form-fields']['children'])){
                    $childs = $paymentComponent['children']['form-fields']['children'];
                    foreach($childs as $key => $child){
                        if(isset($child['config']['template']) && $child['config']['template'] == 'ui/group/group' && isset($child['children'])){
                            $childs[$key]['component'] = "Rokanthemes_OpCheckout/js/view/form/components/group";
                            if (isset($childs[$key]['children'])) {
                                $children = $childs[$key]['children'];
                                $newChildren = array();
                                foreach ($children as $item) {
                                    $item['config']['elementTmpl'] = "Rokanthemes_OpCheckout/form/element/input";
                                    $newChildren[] = $item;
                                }
                                $childs[$key]['children'] = $newChildren;
                            }
                        }
                        if(isset($child['config']) && isset($child['config']['elementTmpl']) && $child['config']['elementTmpl'] == "ui/form/element/input"){
                            $childs[$key]['config']['elementTmpl'] = "Rokanthemes_OpCheckout/form/element/input";
                        }
                        if(isset($child['config']) && isset($child['config']['template']) && $child['config']['template'] == "ui/form/field"){
                            $childs[$key]['config']['template'] = "Rokanthemes_OpCheckout/form/field";
                        }
                        $sortOrder = $this->_helperConfig->getFieldSortOrder($key);
                        if($sortOrder !== false){
                            $childs[$key]['sortOrder'] = $sortOrder;
                        }
                    }
                    $paymentComponent['children']['form-fields']['children'] = $childs;
                    $payments[$paymentCode] = $paymentComponent;
                }
            }
        }
        return $payments;
    }
}
