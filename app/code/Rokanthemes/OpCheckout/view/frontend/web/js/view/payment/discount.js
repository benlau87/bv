define(
    [
        'jquery',
        'ko',
        'uiComponent',
        'Magento_Checkout/js/model/quote',
        'Rokanthemes_OpCheckout/js/action/set-coupon-code',
        'Rokanthemes_OpCheckout/js/action/cancel-coupon',
        'Magento_Checkout/js/action/get-payment-information'
    ],
    function ($, ko, Component, quote, setCouponCodeAction, cancelCouponAction) {
        'use strict';
        var totals = quote.getTotals();
        var couponCode = ko.observable(null);
        if (totals()) {
            couponCode(totals()['coupon_code']);
        }
        var isApplied = ko.observable(couponCode() != null);
        var isLoading = ko.observable(false);
        return Component.extend({
            defaults: {
                template: 'Rokanthemes_OpCheckout/payment/discount'
            },
            couponCode: couponCode,

            isShowDiscount: ko.observable(window.checkoutConfig.show_discount),
            /**
             * Applied flag
             */
            isApplied: isApplied,
            isLoading: isLoading,
            /**
             * Coupon code application procedure
             */
            apply: function() {
                if (this.validate()) {
                    this.showOverlay();
                    isLoading(true);
                    setCouponCodeAction(couponCode(), isApplied, isLoading);
                }
            },
            /**
             * Cancel using coupon
             */
            cancel: function() {
                if (this.validate()) {
                    this.showOverlay();
                    isLoading(true);
                    couponCode('');
                    cancelCouponAction(isApplied, isLoading);
                }
            },

            showOverlay: function () {
                $('#ajax-loader3').show();
                $('#control_overlay_review').show();
            },

            hideOverlay: function () {
                $('#ajax-loader3').hide();
                $('#control_overlay_review').hide();
            },


            /**
             * Coupon form validation
             *
             * @returns {boolean}
             */
            validate: function() {
                var form = '#discount-form';
                return $(form).validation() && $(form).validation('isValid');
            }
        });
    }
);
