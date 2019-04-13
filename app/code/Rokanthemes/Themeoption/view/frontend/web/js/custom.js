require(["jquery"], function($){
    $(document).ready(function() {
    $('.full-width-content').each(function () {
            if ($(this).find('.column_container').length > 1) {
                if ($(window).width() > 768) {
                    var maxHeight = 0;
                    $(this).children('.column_container').css('height', 'auto');
                    $('.full-width-content .column_container').css('min-height', '0');
                    $(this).children('.column_container').each(function () {
                        if ($(this).outerHeight() > maxHeight) {
                            maxHeight = $(this).outerHeight();
                            $(this).outerHeight(maxHeight);
                        } else {
                            $(this).outerHeight(maxHeight);
                        }
                    });
                    $(this).children('.column_container').outerHeight(maxHeight);
                } else {

                    $(this).children('.column_container').css('height', 'auto');
                }

            }
        });

        function formatCustomPrice(price) {
            return price.toLocaleString('de-DE', { style: 'currency', currency: 'EUR', minimumFractionDigits: 2,  maximumFractionDigits: 2});
        }


        /********************************************************** Show Free Shipping Costs *********************************************************************/
        jQuery(document).ready(function() {
            if($('.price-box.price-final_price, .price .after_special').length) {
                jQuery('.product-info-main').find('.tax-details:last-of-type').remove();
                jQuery('#relate_product_slider, #upsell_product_slider, .product-info-main').each(function () {
                    var final_price = $('.price-box.price-final_price span.price', this).html();
                    final_price = final_price.substring(0, final_price.indexOf("&") - 3);
                    final_price = final_price.replace(/\./g, '');

                    if (final_price >= 50) {
                        var tax_details = jQuery(this).find('.tax-details').text();
                        tax_details = tax_details.substring(0, tax_details.indexOf(","));
                        jQuery(this).find('.tax-details').html(tax_details + ', <span class="freeshipping">versandkostenfrei</span>');
                    }
                });

                $('.price-box.price-final_price, .price .price-wrapper').each(function () {
                    var final_price = $(this).find('span.price, span.after_special').html();
                    final_price = final_price.substring(0, final_price.indexOf("&") - 3);
                    final_price = final_price.replace(/\./g, '');

                    if (final_price >= 50) {
                        var tax_details = jQuery(this).parent().find('.tax-details').html();
                        tax_details = tax_details.substring(0, tax_details.indexOf(",") - 1);
                        jQuery(this).parent().find('.tax-details').html(tax_details + ', <span class="freeshipping">versandkostenfrei</span>');
                    }
                });
            }

            var wto;

            jQuery('.product-add-form').find('select').change(function() {
                clearTimeout(wto);
                wto = setTimeout(function() {
                    var productId = $('.price-box').attr('data-product-id');
                    var final_price = $('.price-box.price-final_price span.price').html();
                    final_price = final_price.substring(0, final_price.indexOf("&")-3);
                    final_price = final_price.replace(/\./g,'');

                    var skontoPrice = final_price * 0.98;
                    var skontoPriceSavings = final_price - (final_price * 0.98);
                    var skontoPriceFormatted = formatCustomPrice(skontoPrice);
                    var skontoPriceSavingsFormatted = formatCustomPrice(skontoPriceSavings);

                    $('#skonto-price-' + productId).html(skontoPriceFormatted);
                    $('#skonto-saver-' + productId).html(skontoPriceSavingsFormatted);
                }, 150);

            });

        });
    });
});
