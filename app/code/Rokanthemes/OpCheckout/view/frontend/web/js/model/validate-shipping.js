define(
    [
        'ko'
    ],
    function (
        ko
    ) {
        'use strict';
        return {
            errorValidationMessage:ko.observable(false),
            validating: ko.observable(false)
        }
    }
);
