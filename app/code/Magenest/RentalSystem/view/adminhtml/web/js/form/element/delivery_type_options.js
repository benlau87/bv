/**
 * Copyright © 2019 Magenest. All rights reserved.
 */
define([
    'underscore',
    'uiRegistry',
    'Magento_Ui/js/form/element/select',
    'Magento_Ui/js/modal/modal'
], function (_, uiRegistry, select, modal) {
    'use strict';
    return select.extend({

        initialize: function () {
            this._super();
            var selectedType = this._super().initialValue;
            this.resetVisibility(selectedType);
            return this;
        },

        onUpdate: function (value) {
            var leadTimeField = uiRegistry.get('index = lead_time');
            if (leadTimeField.visibleValue === value) {
                leadTimeField.show();
            } else {
                leadTimeField.hide();
            }

            var addressField = uiRegistry.get('index = pickup_address');
            if (addressField.visibleValue === value) {
                addressField.show();
            } else {
                addressField.hide();
            }
            return this._super();
        },

        resetVisibility: function (selectedType) {
            var leadTimeField = uiRegistry.get('index = lead_time');
            if (leadTimeField.visibleValue === selectedType) {
                leadTimeField.show();

            } else {
                leadTimeField.hide();
            }

            var addressField = uiRegistry.get('index = pickup_address');
            if (addressField.visibleValue === selectedType) {
                addressField.show();
            } else {
                addressField.hide();
            }
        },
    });
});