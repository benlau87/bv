define([
  "jquery"
],
function($) {
  "use strict";

    $('#back-top').click(function () {
        $('body,html').animate({
            scrollTop: 0
        }, 800);
        return false;
    });

});