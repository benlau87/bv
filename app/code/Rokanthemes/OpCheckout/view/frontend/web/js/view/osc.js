define(
    [
        'jquery',
        'uiComponent',
        'ko',
        'mage/translate',
        'Magento_Checkout/js/model/quote',
        'Rokanthemes_OpCheckout/js/action/validate-shipping-info',
        'Rokanthemes_OpCheckout/js/action/showLoader',
        'Rokanthemes_OpCheckout/js/action/save-shipping-address',
        'Rokanthemes_OpCheckout/js/action/set-shipping-information',
        'Rokanthemes_OpCheckout/js/model/shipping-rate-service',
        'Rokanthemes_OpCheckout/js/action/save-additional-information',
        'Magento_Ui/js/modal/alert'
    ],
    function (
        $,
        Component,
        ko,
        $t,
        quote,
        ValidateShippingInfo,
        Loader,
        SaveAddressBeforePlaceOrder,
        setShippingInformationAction,
        shippingRateService,
        saveAdditionalInformation,
        alertPopup
    ) {
        'use strict';
        return Component.extend({
            defaults: {
                template: 'Rokanthemes_OpCheckout/opcheckout'
            },
            errorMessage: ko.observable(),
            isVirtual:quote.isVirtual,
            enableCheckout: ko.pureComputed(function(){
                return (Loader().loading())?false:true;
            }),
            placingOrder: ko.observable(false),
            initialize: function () {
                this._super();
            },
            prepareToPlaceOrder: function(){
                var self = this;
                if (!quote.paymentMethod()) {
                    alertPopup({
                        content: $t('Please choose a payment method!'),
                        autoOpen: true,
                        clickableOverlay: true,
                        focus: "",
                        actions: {
                            always: function(){

                            }
                        }
                    });
                }
                if(self.validateInformation() == true){
					if($("#terms_and_conditions_checkbox").length > 0){
						if($("#terms_and_conditions_checkbox").is(':checked')){
							self.placingOrder(true);
							Loader().all(true);
							var deferred = saveAdditionalInformation();
							deferred.done(function () {
								self.placeOrder();
							});
						}
						else{
							alertPopup({
								title: window.checkoutConfig.terms_and_con_warning,
								content: window.checkoutConfig.terms_and_con_warning_content,
								autoOpen: true,
								clickableOverlay: true,
								focus: "",
								actions: {
									always: function(){

									}
								}
							});
						}
					}
					else{
						self.placingOrder(true);
						Loader().all(true);
						var deferred = saveAdditionalInformation();
						deferred.done(function () {
							self.placeOrder();
						});
					}
                }else{

                }
            },

            placeOrder: function () {
                var self = this;


                SaveAddressBeforePlaceOrder();
                if(this.isVirtual()){
                    if($("#co-payment-form ._active button[type='submit']").length > 0){
                        $("#co-payment-form ._active button[type='submit']").click();
                        self.placingOrder(false);
                        Loader().all(false);
                    }
                }else{
                    setShippingInformationAction().always(
                        function () {
                            shippingRateService().stop(false);
                            if($("#co-payment-form ._active button[type='submit']").length > 0){
                                $("#co-payment-form ._active button[type='submit']").click();
                                self.placingOrder(false);
                                Loader().all(false);
                            }
                        }
                    );
                }
            },

            validateInformation: function(){
                var shipping = (this.isVirtual())?true:ValidateShippingInfo();
                var billing = this.validateBillingInfo();
                return shipping && billing;
            },
            
            afterRender: function(){
                $('#checkout-loader').removeClass('show');
            },
            
            validateBillingInfo: function(){
                if($("#co-payment-form ._active button[type='submit']").length > 0){
                    if($("#co-payment-form ._active button[type='submit']").hasClass('disabled')){
                        if($("#co-payment-form ._active button.update-address-button").length > 0){
                            this.showErrorMessage($t('Please update your billing address'));
                        }
                        return false;
                    }else{
                        return true;
                    }
                }
                return false;
            },
            showErrorMessage: function(message){
                var self = this;
                var timeout = 5000;
                self.errorMessage($t(message));
                setTimeout(function(){
                    self.errorMessage('');
                },timeout);
            }
        });
    }
);
