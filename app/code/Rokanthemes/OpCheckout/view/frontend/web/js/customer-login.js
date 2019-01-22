define([
    'jquery',
    'mage/storage',
    'jquery/ui',
    'mage/mage',
    'Magento_Checkout/js/checkout-data'
], function ($, storage, UI, mage, checkoutData) {
    $.widget("rokanthemes.customerLogin", {
        options: {},

        /**
         * customerLogin creation
         * @protected
         */
        _create: function () {
            var self = this, options = this.options;

            $.extend(this, {
                $loginLinkControl: $('#opcheckout-login-link'),
                $forgotPasswordLink: $('#opcheckout-forgot-password-link'),
                $returnLoginLink: $('#opcheckout-return-login-link'),
                $registerLink: $('#opcheckout-register-link'),
                $returnLoginLinkFromRegister: $('#opcheckout-return-login-link-2'),

                $overlayControl: $('#control_overlay'),

                $loginButton: $('#opcheckout-login-button'),
                $registerButton: $('#opcheckout-register-button'),
                $sendPasswordButton: $('#opcheckout-forgot-button'),

                $loadingContol: $('#opcheckout-login-loading'),
                $forgotPasswordLoading: $('#opcheckout-forgot-loading'),
                $registerLoading: $('#opcheckout-register-loading'),

                $loginError: $('#opcheckout-login-error'),
                $forgotPasswordError: $('#opcheckout-forgot-error'),
                $registerError: $('#opcheckout-register-error'),

                $forgotPasswordSuccess: $('#opcheckout-forgot-success'),

                $usernameInput: $('#id_opcheckout_username'),
                $passwordInput: $('#id_opcheckout_password'),


                $forgotPasswordContent: $('#opcheckout-login-popup-contents-forgot'),
                $loginContent: $('#opcheckout-login-popup-contents-login'),
                $registerContent: $('#opcheckout-login-popup-contents-register'),
                $forgotPasswordTitle: $('.title-forgot'),

                $closeButton: $('.close'),

                $loginPopup: $('#opcheckout-login-popup'),
                $emailForgot: $('#id_opcheckout_email'),


                $firstNameReg: $('#id_opcheckout_firstname'),
                $lastNameReg: $('#id_opcheckout_lastname'),
                $userNameReg: $('#id_opcheckout_register_username'),
                $passwordReg: $('#id_opcheckout_register_password'),
                $confirmReg: $('#id_opcheckout_register_confirm_password'),

                $loginTable: $('#opcheckout-login-table'),
                $forgotPasswordTable: $('#opcheckout-forgot-table'),
                $registerTable: $('#opcheckout-register-table')


            });

            this.$loginLinkControl.click(function () {
                self.resetFormLogin();
                $(self.element).show();
                self.$overlayControl.show();
            });

            this.$overlayControl.click(function () {
                $(self.element).hide();
                $(this).hide();
                $('#opcheckout-toc-popup').hide();
            });

            self.validateLoginForm();
            self.validateRegisterForm();
            self.validateForgotForm();

            $(document).keypress(function (e) {
                if (e.which == 13) {
                    if (self.$loginContent.is(':visible')) {
                        $('#opcheckout-login-form').submit(function () {
                            return false;
                        });
                        self.validateLoginForm();
                    } else if (self.$forgotPasswordContent.is(':visible')) {
                        $('#opcheckout-register-form').submit(function () {
                            return false;
                        });
                        self.validateRegisterForm();
                    } else if (self.$registerContent.is(':visible')) {
                        $('#opcheckout-forgot-form').submit(function () {
                            return false;
                        });
                        self.validateForgotForm();
                    }
                }
            });
            this.$forgotPasswordLink.click(function () {
                self.$loginContent.hide();
                self.$forgotPasswordContent.show();
            });

            this.$returnLoginLink.click(function () {
                self.$forgotPasswordContent.hide();
                self.$loginContent.show();
            });

            this.$registerLink.click(function () {
                self.$loginContent.hide();
                self.$loginPopup.addClass('absolute-box');
                self.$registerContent.show();
            });

            this.$returnLoginLinkFromRegister.click(function () {
                self.$registerContent.hide();
                self.$loginPopup.removeClass('absolute-box');
                self.$loginPopup.addClass('fixed-box');
                self.$loginContent.show();
            });

            this.$closeButton.click(function () {
                self.$loginPopup.hide();
                self.$overlayControl.hide();
                $('#opcheckout-toc-popup').hide();
            });

            $('#opcheckout-toc-link').click(function (e) {
                self.$overlayControl.show();
                e.preventDefault();
                $('#opcheckout-toc-popup').show();
            })

        },
        validateLoginForm: function () {
            var self = this;
            $('#opcheckout-login-form').mage('validation', {
                submitHandler: function (form) {
                    self.ajaxLogin();
                }
            });
        },
        validateRegisterForm: function () {
            var self = this;
            $('#opcheckout-register-form').mage('validation', {
                submitHandler: function (form) {
                    self.ajaxRegister();
                }
            });
        },
        validateForgotForm: function () {
            var self = this;
            $('#opcheckout-forgot-form').mage('validation', {
                submitHandler: function (form) {
                    self.ajaxForgotPassword();
                }
            });
        },
        ajaxLogin: function () {
            var self = this, options = this.options;
            self.$loadingContol.show();
            self.$loginTable.hide();
            self.$loginError.hide();
            var params = {
                username: self.$usernameInput.val(),
                password: self.$passwordInput.val()
            };
            storage.post(
                'opcheckout/account/login',
                JSON.stringify(params),
                false
            ).done(
                function (result) {
                    var errors = result.errors;
                    if (errors == false) {
                        self.$loadingContol.show();
                        checkoutData.setShippingAddressFromData({});
                        window.location.reload();
                    } else {
                        self.$loadingContol.hide();
                        self.$loginTable.show();
                        self.$loginError.html(result.message);
                        self.$loginError.show();
                    }
                }
            ).fail(
                function (result) {

                }
            );
        },
        ajaxRegister: function () {
            var self = this, options = this.options;
            if (self.$passwordReg.val() == self.$confirmReg.val()) {
                self.$registerLoading.show();
                self.$registerTable.hide();
                self.$registerError.hide();
                var params = {
                    firstname: self.$firstNameReg.val(),
                    lastname: self.$lastNameReg.val(),
                    email: self.$userNameReg.val(),
                    password: self.$passwordReg.val(),
                    password_confirmation: self.$confirmReg.val()
                };
                storage.post(
                    'opcheckout/account/register',
                    JSON.stringify(params),
                    false
                ).done(
                    function (result) {
                        self.$registerLoading.hide();
                        var success = result.success;
                        if (!result.error) {
                            checkoutData.setShippingAddressFromData({});
                            window.location.reload();
                        } else {
                            self.$registerTable.show();
                            self.$registerError.html(result.error);
                            self.$registerError.show();
                        }
                    }
                ).fail(
                    function (result) {

                    }
                );
            } else {
                alert("Please Re-Enter Confirmation Password !");
            }
        },
        ajaxForgotPassword: function () {
            var self = this, options = this.options;
            self.$forgotPasswordError.hide();
            self.$forgotPasswordLoading.show();
            self.$forgotPasswordTable.hide();
            var params = {
                email: self.$emailForgot.val()
            };
            storage.post(
                'opcheckout/account/forgotPassword',
                JSON.stringify(params),
                false
            ).done(
                function (result) {
                    self.$forgotPasswordLoading.hide();
                    var success = result.success;
                    if (success == 'true') {
                        self.$forgotPasswordSuccess.show();
                        self.$forgotPasswordTable.hide();
                        self.$forgotPasswordTitle.hide();
                    } else {
                        self.$forgotPasswordTable.show();
                        self.$forgotPasswordError.html(result.errorMessage);
                        self.$forgotPasswordError.show();
                    }
                }
            ).fail(
                function (result) {

                }
            );
        },
        resetFormLogin: function () {
            var self = this;
            self.$loginTable.show();
            self.$forgotPasswordTable.show();
            self.$registerTable.show();

            self.$loadingContol.hide();
            self.$forgotPasswordLoading.hide();
            self.$registerLoading.hide();

            self.$loginError.hide();
            self.$forgotPasswordError.hide();
            self.$registerError.hide();

            self.$loginContent.show();
            self.$forgotPasswordContent.hide();
            self.$registerContent.hide();
        }
    });

    return $.rokanthemes.customerLogin;
});