define(
    [
        'jquery',
        'uiComponent',
        'ko',
        'mage/translate',
        'mage/storage',
        'Magento_Checkout/js/model/full-screen-loader'
    ],
    function($, Component, ko, $t, storage, fullScreenLoader) {
        'use strict';

        return Component.extend({

            oneStepTitle: ko.observable(window.checkoutConfig.checkout_title),
            oneStepDescription: ko.observable(window.checkoutConfig.checkout_description),
            isShowLoginLink: ko.observable(window.checkoutConfig.show_login_link),
            isLogin: ko.observable(window.checkoutConfig.is_login),
            loginLinkTitle: ko.computed(function(){
                if (window.checkoutConfig.login_link_title) {
                    return window.checkoutConfig.login_link_title;
                } else {
                    return $t('Click here to login or create a new account');
                }
            }),

            defaults: {
                template: 'Rokanthemes_OpCheckout/before-form'
            },

            showLoginForm: function () {
                $('#opcheckout-login-popup').show();
                $('#control_overlay').show();
            },


            logout: function () {
                var params = {};
                $('body').removeClass('oscHideLoader');
                fullScreenLoader.startLoader();
                storage.post(
                    'opcheckout/account/logout',
                    JSON.stringify(params),
                    false
                ).done(
                    function (result) {
                    }
                ).fail(
                    function (result) {

                    }
                ).always(
                    function (result) {
                        window.location.reload();
                    }
                );
            }


        });
    }
);
