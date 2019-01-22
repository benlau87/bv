define([
    'jquery',
    'Magento_Checkout/js/view/form/element/email',
    'ko',
    'Magento_Customer/js/model/customer',
    'Magento_Checkout/js/model/quote',
    'Magento_Checkout/js/checkout-data'
], function ($, Email, ko, customer, quote, checkoutData) {
    'use strict';

    var validatedEmail = checkoutData.getValidatedEmailValue();

    if (validatedEmail && !customer.isLoggedIn()) {
        quote.guestEmail = validatedEmail;
    }

    return Email.extend({
        defaults: {
            template: 'Rokanthemes_OpCheckout/form/element/email',
            email: checkoutData.getInputFieldEmailValue(),
            emailFocused: false,
            isLoading: false,
            isPasswordVisible: false,
            listens: {
                email: 'emailHasChanged',
                emailFocused: 'validateEmail'
            }
        },
        checkDelay: 1000,
        initialize: function () {
            this._super();
            return this;
        },
        afterRenderEmail: function(){
            var self = this;
            if(self.email()){
                self.emailHasChanged();
            }
        },
        showLoginPopup: function(){
            $('#opcheckout-login-popup').show();
            $('#control_overlay').show();
            $('#opcheckout-return-login-link').click();
            $('#id_opcheckout_username').val(this.email());
            //$('#id_opcheckout_password').focus();
            this.scrollScreen();
        },
        forgotPassword: function(){
            $('#opcheckout-login-popup').show();
            $('#control_overlay').show();
            $('#opcheckout-forgot-password-link').click();
            $('#id_opcheckout_email').val(this.email());
            //$('#id_opcheckout_email').focus();
            this.scrollScreen();
        },
        scrollScreen: function(){
            $("html, body").animate({ scrollTop: 0 }, 500);
        },

        changeValue: function () {
            var self = this;
            if (self.email()) {
                $('#customer-email').addClass('email-has-data');
            } else {
                $('#customer-email').removeClass('email-has-data');
            }
        }
    });
});
