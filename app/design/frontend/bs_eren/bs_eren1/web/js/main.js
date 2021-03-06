define(['jquery', 'mage/mage', 'rokanthemes/owl'], function($){
    "use strict";
    return function main()
    {
        $('#back-top').click(function () {
            $('body,html').animate({
                scrollTop: 0
            }, 800);
            return false;
        });

        $(".rokan-category-best .owl, .rokan-onsaleproduct .owl, .rokan-newproduct .owl, #upsell_product_slider").owlCarousel({
            lazyLoad: true,
            autoPlay: false,
            items: 4,
            itemsDesktop: [1199, 4],
            itemsDesktopSmall: [980, 3],
            itemsTablet: [768, 2],
            itemsMobile: [479, 1],
            slideSpeed: 500,
            paginationSpeed: 500,
            rewindSpeed: 500,
            navigation: true,
            stopOnHover: true,
            pagination: false,
            scrollPerPage: true
        });

        $('.product-view .product-info-main .col-cart').mage('sticky', {
            container: '#maincontent'
        });

        $(document).ready(function() {
            var colcartHeight = $('.col-cart').outerHeight();
            $('.view-product').css('min-height', colcartHeight + 150);

            $('select[name="field_31[day]"]:nth-of-type(1), select[name="field_31[month]"]:nth-of-type(3)').remove();
        });
        
    }
});
