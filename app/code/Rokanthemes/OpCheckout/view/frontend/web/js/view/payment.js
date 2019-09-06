define(
    [
        'jquery',
        "underscore",
        'ko',
        'Magento_Checkout/js/model/payment-service',
        'Magento_Checkout/js/model/payment/method-converter',
        'mage/translate',
        'Magento_Checkout/js/view/payment',
        'Magento_Checkout/js/model/quote',
        'Magento_Checkout/js/model/payment/method-list',
        'Rokanthemes_OpCheckout/js/action/save-default-payment',
		'Magento_Checkout/js/action/get-payment-information'
    ],
    function (
        $,
        _,
        ko,
        paymentService,
        methodConverter,
        $t,
        Payment,
        quote,
        methodList,
        saveDefaultPayment,
		getPaymentInformation
    ) {
        'use strict';

        /** Set payment methods to collection */
        paymentService.setPaymentMethods(methodConverter(window.checkoutConfig.paymentMethods));

        return Payment.extend({
            defaults: {
                template: 'Rokanthemes_OpCheckout/payment',
                activeMethod: ''
            },
            initialize: function () {
                this.beforeInitPayment();
                this._super();
                this.navigate();
                methodList.subscribe(function () {
                    saveDefaultPayment();
                });
                return this;
            },
			navigate: function () {
				var self = this;
				if (!self.hasShippingMethod()) {
					this.isVisible(true);
				} else {
					getPaymentInformation().done(function () {
						self.isVisible(true);
					});
				}
			},
            beforeInitPayment: function(){
                /*
                 * 10/09/2016 - Daniel
                 * fix conflict js braintree 
                 */
                quote.shippingAddress.subscribe(function(){
                    if(quote.shippingAddress() && !quote.shippingAddress().street){
                        quote.shippingAddress().street = ['',''];
                    }
                });
                /* End */
            }
        });
    }
);
