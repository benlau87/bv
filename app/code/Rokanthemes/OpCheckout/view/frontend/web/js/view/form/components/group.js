define([
    'Magento_Ui/js/form/components/group'
], function (Group) {
    'use strict';

    return Group.extend({
        defaults: {
            visible: true,
            label: '',
            showLabel: true,
            required: false,
            template: 'ui/group/group',
            fieldTemplate: 'Rokanthemes_OpCheckout/form/field',
            breakLine: true,
            validateWholeGroup: false,
            additionalClasses: {}
        },

        /**
         * Extends this with defaults and config.
         * Then calls initObservable, iniListenes and extractData methods.
         */
        initialize: function () {
            this._super();
            return this;
        }
    });
});
