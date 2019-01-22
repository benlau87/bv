define([
    'jquery',
    'Magento_Checkout/js/view/shipping-address/address-renderer/default',
    'Magento_Checkout/js/model/shipping-address/form-popup-state'
], function($, Renderer, formPopUpState) {
    'use strict';
    return Renderer.extend({
        isFormPopUpVisible: formPopUpState.isVisible,
        defaults: {
            template: 'Rokanthemes_OpCheckout/shipping-address/address-renderer/default'
        },
        editAddress: function() {
            this.showForm();
        },
        showForm: function() {
            formPopUpState.isVisible(true);
        }
    });
});
