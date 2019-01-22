define(
    [
        'jquery',
        'Magento_Checkout/js/view/summary/abstract-total'
    ],
    function ($, Component) {
        'use strict';

        return Component.extend({
            /**
             * @return {*}
             */
            isDisplayed: function () {
                return this.isFullMode();
            }
        });
    }
);
