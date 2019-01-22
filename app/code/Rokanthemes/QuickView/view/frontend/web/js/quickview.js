/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
define([
    'jquery',
	'mage/template',
    'mage/translate',
    'jquery/ui',
	'quickview/cloudzoom',
	'rokanthemes/owl'
], function($, mageTemplate) {
    "use strict";

    $.widget('mage.productQuickview', {
		loaderStarted: 0,
        options: {
            icon: '',
            texts: {
                loaderText: $.mage.__('Please wait...'),
                imgAlt: $.mage.__('Loading...')
            },
            template:'<div class="loading-mask" data-role="loader">' +
                    '<div class="loader">' +
                        '<img alt="<%- data.texts.imgAlt %>" src="<%- data.icon %>">' +
                        '<p><%- data.texts.loaderText %></p>' +
                    '</div>' + '</div>'

        },

        _create: function() {
			this._bindClick();
        },

        _bindClick: function() {
            var self = this;
			self.createWindow();
            this.element.on('click', function(e) {
                e.preventDefault();
				self.element.removeClass('active');
				$(this).addClass('active');
				self.show();
                self.ajaxLoad($(this));
            });
        },
		_render: function () {
            var html;

            if (!this.spinnerTemplate) {
                this.spinnerTemplate = mageTemplate(this.options.template);

                html = $(this.spinnerTemplate({
                    data: this.options
                }));

                html.prependTo($('body'));

                this.spinner = html;
            }
        },
		show: function (e, ctx) {
            $('body').append("<div id='fancybox-loading'><div></div></div>");
            return false;
        },
		hide: function () {
            $('body #fancybox-loading').remove();
            return false;
        },

        ajaxLoad: function(link) {
            var self = this;
			if($('#quickview-content-' + link.attr('data-id')).length > 0)
			{
				return self.showWindow($('#quickview-content-' + link.attr('data-id')));
			}
			var urlLink = link.attr('data-href');
			if(!urlLink)
				urlLink = link.attr('href');
            $.ajax({
                url: urlLink,
                data: {},
                success: function(res) {
					var itemShow = $('#quickview-content');
					if(link.attr('data-id'))
					{
						if($('#quickview-content-' + link.attr('data-id')).length < 1)
						{
							var wrapper = document.createElement('div');
							$(wrapper).attr('id', 'quickview-content-' + link.attr('data-id'));
							$(wrapper).addClass('wrapper_quickview_item');
							$(wrapper).html(res);
							$('#quickview-content').append(wrapper);
						}
						itemShow = $('#quickview-content-' + link.attr('data-id'));
						$('#quickview-content-' + link.attr('data-id') + ' .owl .small_image').on('click', function(event){
							$('#quickview-content-' + link.attr('data-id') + ' .owl .small_image').removeClass('active');
							$(this).addClass('active');
							var currentImg = $(this).children('img');
							jQuery('#gallery_' + link.attr('data-id') + ' a.cloud-zoom').attr('href', currentImg.attr('data-href'));
							jQuery('#gallery_' + link.attr('data-id') + ' a.cloud-zoom img').attr('src', currentImg.attr('data-thumb-image'));
							$('.cloud-zoom, .cloud-zoom-gallery').CloudZoom();
						});
						$('#quickview-content-' + link.attr('data-id') + ' .owl').owlCarousel({
							autoPlay : false,
							items : 4,
							itemsDesktop : [1199,4],
							itemsDesktopSmall : [980,3],
							itemsTablet: [768,2],
							itemsMobile : [479,1],
							slideSpeed : 500,
							paginationSpeed : 500,
							rewindSpeed : 500,
							navigation : true,
							stopOnHover : true,
							pagination :false,
							scrollPerPage:true,
						});
					}else
					{
						$('#quickview-content').html(res);
					}
					$('.cloud-zoom, .cloud-zoom-gallery').CloudZoom();
					$('#quickview-content').trigger('contentUpdated');
					self.showWindow(itemShow);
                }
            });
        },
		showWindow: function(itemShow)
		{	
			this.hide();
			var scrollY = $(window).scrollTop();
			var width = $('body').width();
			$('#quick-window .wrapper_quickview_item').hide();
			$('#quick-window').css({
						top:  scrollY + 100 + 'px',	
						left:  width/2 - $('#quick-window').width()/2 + 'px', 
						display: 'block'
			});
			if(itemShow)
				itemShow.show();
			$('#quick-background').removeClass('hidden');
		},
		hideWindow: function()
		{
			$('#quick-window').hide();
			$('#quick-window .wrapper_quickview_item').hide();
			$('#quick-background').addClass('hidden');
			$('#quickview-content').html('');
		},
		createWindow: function()
		{
			if($('#quick-background').length > 0)
				return;
			var qBackground = document.createElement('div');
			$(qBackground).attr('id', 'quick-background');
			$(qBackground).addClass('hidden');
			$('body').append(qBackground);
			
			var qWindow = document.createElement('div');
			$(qWindow).attr('id', 'quick-window');
			$(qWindow).html('<div id="quickview-header"><a href="javascript:void(0)" id="quickview-close">close</a></div><div class="quick-view-content" id="quickview-content"></div>');
			$('body').append(qWindow);
			$('#quickview-close').on('click', this.hideWindow.bind(this));
		}
    });

    return $.mage.productQuickview;
});