/**
 * Customer store credit(balance) application
 */
/*global define,alert*/
define(
    [
        'ko',
        'jquery',
        'mage/storage'
    ],
    function (ko, $, storage) {
        'use strict';
        return function () {
            var deferred = $.Deferred();
            var newsletter = '';

            if ($('#newsletter_subscriber_checkbox').length > 0) {
                if ($('#newsletter_subscriber_checkbox').attr('checked') == 'checked') {
                    newsletter = 1;
                } else {
                    newsletter = 0;
                }
            }
            
            var params = {
                'osc_newsletter': newsletter
            };
            storage.post(
                'opcheckout/index/saveCustomCheckoutData',
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
                    deferred.resolve(result);
                }
            );
            return deferred;
        };
    }
);
