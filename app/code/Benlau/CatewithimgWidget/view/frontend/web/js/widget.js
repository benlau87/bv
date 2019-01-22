define([
        "jquery"
    ],
    function ($) {
        "use strict";

        $(document).ready(function () {
            $("#catwithimg").find('li.item-inner').click(function () {
                window.location = $(this).find("a.category-button").attr("href");
                return false;
            });
        });

    });