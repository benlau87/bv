define(
    [
        'jquery',
        'ko',
        'Magento_Checkout/js/view/billing-address',
        'Rokanthemes_OpCheckout/js/model/billing-address-state',
        'Magento_Checkout/js/model/quote'
    ],
    function (
        $,
        ko,
        BillingAddress,
        State,
        quote
    ) {
        'use strict';

        return BillingAddress.extend({
            isVirtual:quote.isVirtual,
            addressSameAsShipping: State.sameAsShipping,
            defaults: {
                template: 'Rokanthemes_OpCheckout/billing-address'
            },

            canNotUseBillingDifferent: ko.computed(function () {
                return (!(window.checkoutConfig.show_shipping_address));
            }),
            /**
             * Init component
             */
            initialize: function () {
                this._super();
            },
            /**
             * @return {Boolean}
             */
            checkUseShippingAddress: function (data,event) {
                var useShipping = event.target.checked?true:false;
                State.sameAsShipping(useShipping);
                if(useShipping == false){
                    this.editAddress();
                }
                return true;
            },
            /**
             * @return {exports.initObservable}
             */
            initObservable: function () {
                this._super();
                quote.billingAddress.subscribe(function (newAddress) {
                    if (quote.isVirtual()) {
                        State.sameAsShipping(false);
                    }
                }, this);

                return this;
            },
            /**
             * Cancel address edit action
             */
            cancelAddressEdit: function () {
                this.restoreBillingAddress();
                if (quote.billingAddress()) {
                    State.sameAsShipping(
                        quote.billingAddress() != null &&
                            quote.billingAddress().getCacheKey() == quote.shippingAddress().getCacheKey() &&
                            !quote.isVirtual()
                    );
                    this.isAddressDetailsVisible(true);
                }
            },
            /**
             * sort the address field base on sortOrder
             * @param {UIclass} fields
             * @returns {Boolean}
             */
            sortFields: function(fields){
                if(fields.elems().length > 0){
                    var allFields = fields.elems();

                    var regionId, region;
                    $.each(allFields, function (index, value) {

                        if (value.inputName == 'region_id') {
                            regionId = value;
                        }
                        if (value.inputName == 'region') {
                            region = value;
                        }
                    });


                    if(regionId && region){
                        region.sortOrder = regionId.sortOrder;
                    }

                    fields.elems().sort(function(fieldOne, fieldTwo){
                        return parseFloat(fieldOne.sortOrder) > parseFloat(fieldTwo.sortOrder) ? 1 : -1
                    });
                }
                return true;
            },
            initGoogleAddress: function(){
            },
            editAddress: function(){
                this._super();
                this.initGoogleAddress();
            }
        });
    }
);
