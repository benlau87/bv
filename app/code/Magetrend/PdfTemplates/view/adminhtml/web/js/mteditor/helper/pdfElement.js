/**
 * MB "Vienas bitas" (Magetrend.com)
 *
 * @category MageTrend
 * @package  Magetend/PdfTemplates
 * @author   Edvinas Stulpinas <edwin@magetrend.com>
 * @license  http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link     https://www.magetrend.com/magento-2-pdf-invoice-pro
 */
var pdfElement = (function($){
    var config = {

    };

    var init = function(options) {
        $.extend(config, options);
        initElements();
        pdfElement.reloadEvents(true);
    };

    var initElements = function () {
        var elementData = config.data;
        var elementTemplate = config.elements;

        if (config.elements.length == 0) {
            return;
        }

        $.each(elementData, function (index, element) {
            if (!elementTemplate[element.type]) {
                return;
            }
            var newElement = $(elementTemplate[element.type]['html']);
            newElement
                .attr('id', element.uid)
                .css({
                    'z-index': element.sort_order,
                })
                .addClass('pdf-element')
                .data('element-type', element.type)
                .data('element-id', element.uid)

            $('.paperA4.pdf-page-'+element.page_id).prepend(newElement);

            var elementConfig = elementTemplate[element.type]['config'];
            if (!elementConfig['attributes']) {
                return;
            }

            $.each(elementConfig['attributes'], function (key, options) {
                if (!element['attributes'] || !element['attributes'][key] || !options['initEvent']) {
                    return ;
                }
                var elementValue = element.attributes[key];
                eval(options.initEvent+'(elementValue, \''+element.uid+'\', \''+options.className+'\', \''+options.attribute+'\');');
            });
        });
    };

    var reloadEvents = function (removeEvents) {

        $('.pdf-element').unbind('click').click(function (e) {
            if (removeEvents) {
                removeAllHelpers();
            }
            var element = $(this);
            element.addClass('active');
            addHelpers(element);
            initEvents(element);
            mtEditor.opentPropertiesTools();
        });

        $(document).mousedown(function(e) {
            var container = $('.pdf-element');

            if ($(e.target).attr('id') == 'pdf_settings' || $(e.target).closest('pdf_settings')) {
                return;
            }

            if (!container.is(e.target) && container.has(e.target).length === 0) {
                removeAllHelpers();
            }
        });
    };

    var removeAllHelpers =  function () {
        $('.pdf-element .ui-resizable').resizable("destroy");
        $('.pdf-element.ui-resizable').resizable("destroy");
        $('.pdf-element.active').removeClass('active');
        $('.pdf-element-helper').remove();
        $('.pdf-element.ui-resizable-handle').remove();
        $('.pdf-element .ui-resizable-handle').remove();
    };

    var addHelpers = function (element) {

        if (element.is("div")) {
            addDivHelpers(element);
        }

        if (element.is("table")) {
            addTableHelpers(element);
        }
    };

    var addDivHelpers = function (element)
    {
        if (element.attr('data-lock') == 'true' || element.attr('data-lock') == 1) {
            return;
        }
        element.prepend(
            '<div class="pdf-element-helper"><div class="pdf-element-actions">'
            +'<a href="#move"><span class="glyphicon glyphicon-move" aria-hidden="true"></span></a>'
            +'<a href="#remove"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></a>'
            +'</div></div>');

        if (element.data('resizable') == true) {
            var keepRatio = false;
            var handleResize = {};
            if (element.data('resizable-handle')) {
                var handlesType = element.data('resizable-handle');
                if (handlesType == 'table') {
                    var handleResize = {
                        'e': '#egrip',
                        'w': '#wgrip'
                    };
                }

                if (handlesType == 'keep-ratio') {
                    var handleResize = {
                        'nw': '#nwgrip',
                        'ne': '#negrip',
                        'sw': '#swgrip',
                        'se': '#segrip',
                    };
                    keepRatio = true;
                }
            } else {
                var handleResize = {
                    'nw': '#nwgrip',
                    'ne': '#negrip',
                    'sw': '#swgrip',
                    'se': '#segrip',
                    'n': '#ngrip',
                    'e': '#egrip',
                    's': '#sgrip',
                    'w': '#wgrip'
                };
            }

            $.each(handleResize, function (name, id) {
                var idName = id.replace('#', '');
                element.prepend('<div class="ui-resizable-handle ui-resizable-'+name+' '+ idName +'" id="'+idName+'"></div>');
            });

            element.resizable({
                /**containment: ".paperA4.active",**/
                handles: handleResize,
                aspectRatio:keepRatio,
                stop: function(e, ui) {
                    mtEditor.initPropertiesBlock('pdf_element_general');
                },
            });
        }
    }

    var addTableHelpers = function (element) {
        element.prepend(
            '<div class="pdf-element-helper"><div class="pdf-element-actions">'
            +'<a href="#move"><span class="glyphicon glyphicon-move" aria-hidden="true"></span></a>'
            +'<a href="#remove"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></a>'
            +'</div></div>');

        if (element.data('resizable') == true) {
            element.find('th')
                .resizable({
                    /**containment: ".paperA4.active",**/
                    handles: 'e',
                    minWidth: 18
                });

            element.find('tr')
                .resizable({
                    /**containment: ".paperA4.active",**/
                    handles: 's',
                    minWidth: 18
                });
        }
    };

    var setup = function (element) {
    };

    var initEvents = function (element) {
        element.draggable({
            handle: 'a[href="#move"]',
             /* containment: ".paperA4.active",*/
             scroll: true
        });


        $('.pdf-element-actions a[href="#remove"]').unbind('click').click(function (event) {
            event.stopPropagation();
            removeElement();
        });
    };

    var removeElement = function () {
        var element = $('.pdf-element.active');
        popup.confirm({
            'msg': 'Are you sure you want to delete this element?',
            'disableAutoClose': true
        }, function(){
            element.remove();
            popup.close();
        }, function(){
            popup.close();
        });
    };

    var elementUpdateCss = function (value, elementId, className, attribute) {
        var element = getElement(elementId, className);
        if (element) {
            element.css(attribute, value);
        }
    };

    var elementUpdateAttribute = function (value, elementId, className, attribute) {
        var element = getElement(elementId, className);
        if (element) {
            element.attr(attribute, value);
        }
    };

    var elementGetAttribute = function (elementId, className, attribute) {
        var element = getElement(elementId, className);

        if (element) {
            return element.attr(attribute);
        }
        return '';
    };

    var elementGetCss = function (elementId, className, attribute) {
        if ($('#'+elementId).hasClass(className.replace('.', ''))) {
            var element = $('#'+elementId);
        } else {
            var element = $('#'+elementId+' '+className);
        }

        if (element) {
            return element.css(attribute);
        }
        return '';
    };

    var getTextByClass = function (elementId, className) {
        var element = getElement(elementId, className);
        if (element) {
            var htmlContent = element.html()
                .split('<br>').join('{br}')
                .split('<div>').join('{br}')
                .split('</div>').join('');
            return $('<div>'+htmlContent+'</div>').text();
        }
        return '';
    };

    var setTextByClass = function (value, elementId, className, attribute) {
        var element = getElement(elementId, className);
        if (element) {
            return element.html(value.split('{br}').join('<br>'));
        }
        return '';
    };


    var setDataAttribute = function (value, elementId, className, attribute) {
        var element = getElement(elementId, className);
        if (element) {
            element.attr('data-'+attribute, value);
        }
    };

    var getDataAttribute = function (elementId, className, attribute) {
        var element = getElement(elementId, className);

        if (element) {
            return element.attr('data-'+attribute);
        }
        return '';
    };

    var getDataAttributeName = function (elementId, className, attribute) {
        var element = getElement(elementId, className);

        if (element && element.attr('data-'+attribute)) {
            return element.attr('data-'+attribute);
        }
        return '';
    };

    var getElement = function (elementId, className) {
        if ($('#'+elementId).hasClass(className.replace('.', ''))) {
            var element = $('#'+elementId);
        } else {
            var element = $('#'+elementId+' '+className);
        }
        return element;
    };

    var  rotateElement = function (value, elementId, className, attribute) {
        var element = getElement(elementId, className);
        if (element) {
            element.css({
                '-ms-transform': 'rotate('+value+')',/* IE 9 */
                '-webkit-transform': 'rotate('+value+')',/* Safari */
                'transform': 'rotate('+value+')'/* Standard syntax */
            });
        }
    };

    var getRotation = function (elementId, className, attribute) {
        var element = getElement(elementId, className);
        if (element) {
            return getRotationDegrees(element)+'deg';
        }
        return '';
    };

    var getElementNameOptions = function (elementId, className, attribute) {
        var options = {};
        $('[data-name]').each(function () {
            options[$(this).attr('id')] = $(this).data('name');
        });
        return options;
    };


    var getRotationDegrees = function(obj) {
        var matrix = obj.css("-webkit-transform") ||
            obj.css("-moz-transform")    ||
            obj.css("-ms-transform")     ||
            obj.css("-o-transform")      ||
            obj.css("transform");
        if(matrix !== 'none') {
            var values = matrix.split('(')[1].split(')')[0].split(',');
            var a = values[0];
            var b = values[1];
            var angle = Math.round(Math.atan2(b, a) * (180/Math.PI));
        } else { var angle = 0; }
        return (angle < 0) ? angle + 360 : angle;
    }

    var toggleTableColumn = function (value, elementId, className, attribute) {
        setDataAttribute(value, elementId, className, attribute);
        var element = getElement(elementId, className);

        var columnName = '.column-'+attribute.replace('hide-', '');
        var columnIndex = $(columnName).parent().children().index($(columnName));

        if (value == true || value == 'true' || value == 1) {
            element.find(columnName).hide();
            $('#'+elementId+' td:nth-child('+(columnIndex+1)+')').hide();
        } else {
            element.find(columnName).show();
            $('#'+elementId+' td:nth-child('+(columnIndex+1)+')').show();
        }
    };

    var setInsideBorders = function (value, elementId, className, attribute) {
        var element = $('#'+elementId+' '+ className);

        var rowElement = $('#'+elementId+' .pdf-table-item');
        var columnCount = $(rowElement[0]).find('td').length;
        var rowCount = element.length / columnCount;

        var i = 1;
        element.each(function () {
            i++;
            var rowIndex = parseInt(i/columnCount);

            if ($(this).index() == 0 && attribute == 'border-left-width'){
                $(this).css(attribute, 0);
                return;
            }

            if (attribute == 'border-right-width' && $(this).index() == element.length-1) {
                $(this).css(attribute, 0);
                return;
            }

            $(this).css(attribute, value);
        });
    };

    var getInsideBorders = function (elementId, className, attribute) {
        var element = $('#'+elementId+' '+ className);
        var value = '0px';

        var rowElement = $('#'+elementId+' .pdf-table-item');
        var columnCount = $(rowElement[0]).find('td').length;
        var rowCount = element.length / columnCount;

        var i = 0;
        element.each(function () {
            i++;
            var rowIndex = parseInt(i/columnCount);

            if ($(this).index() == 0 && attribute == 'border-left-width'){
                return;
            }

            if (attribute == 'border-right-width' && $(this).index() == element.length-1) {
                return;
            }

            if ($(this).css(attribute)) {
                value = $(this).css(attribute);
            }
            return;
        });
        return value;
    };

    var updateColorScheme = function (color, elementList) {

    };

    var initProductImage = function (value, elementId, className, attribute) {
        if (value == 'true' || value === true || value == 1) {
            $('input[name="show_image"]').trigger('click');
        } else {
            $('input[name="show_image"]').prop('checked', false);
        }

        toggleProductImage(value, elementId, className, attribute);
    }

    var toggleProductImage = function (value, elementId, className, attribute) {
        var imgElements = $('.product-image');
        if (value == true || value == 1 || value == 'true') {
            imgElements.show();
            fixProductNamePosition(true);
        } else {
            imgElements.hide();
            fixProductNamePosition(false);
        }

        return setDataAttribute(value, elementId, className, attribute);
    };

    var imageUpdateCss = function (value, elementId, className, attribute) {
        elementUpdateCss(value, elementId, className, attribute);
        fixProductNamePosition($('.product-image').is(':visible'));
    };

    var fixProductNamePosition = function (isChecked) {
        var productImage = $('.product-image');
        var width = productImage.width();
        var marginLeft = productImage.css('margin-left').replace('px', '');
        var marginRight = productImage.css('margin-right').replace('px', '');
        var productNamePadding = parseInt(width) + parseInt(marginLeft) + parseInt(marginRight);

        if (isChecked) {
            $('.product-name-line-1, .product-name-line-2').css('padding-left', productNamePadding+'px');
        } else {
            $('.product-name-line-1, .product-name-line-2').css('padding-left', '0');
        }
    };

    return {
        init: init,
        reloadEvents: reloadEvents,
        elementUpdateCss: elementUpdateCss,
        elementGetCss: elementGetCss,
        getTextByClass: getTextByClass,
        setTextByClass: setTextByClass,
        elementUpdateAttribute: elementUpdateAttribute,
        elementGetAttribute: elementGetAttribute,
        getRotation: getRotation,
        rotateElement: rotateElement,
        getDataAttribute: getDataAttribute,
        getDataAttributeName: getDataAttributeName,
        setDataAttribute: setDataAttribute,
        toggleTableColumn: toggleTableColumn,
        getElementNameOptions: getElementNameOptions,
        updateColorScheme: updateColorScheme,
        setInsideBorders: setInsideBorders,
        getInsideBorders: getInsideBorders,
        toggleProductImage: toggleProductImage,
        imageUpdateCss: imageUpdateCss,
        initProductImage: initProductImage
    };
})(jQuery);