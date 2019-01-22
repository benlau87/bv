define(
    [
        'ko',
        'uiComponent'
    ],
    function(ko, Component) {
        'use strict';
        return Component.extend({
            defaults: {
                template: 'Rokanthemes_OpCheckout/sign-up-newsletter'
            },

            isShowNewsletter: ko.observable(window.checkoutConfig.show_newsletter),

            isChecked: ko.observable(window.checkoutConfig.newsletter_default_checked)
        });
    }
);
