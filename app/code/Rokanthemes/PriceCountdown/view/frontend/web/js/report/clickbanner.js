/**
 * @File Name: clickbanner.js
 * @File Path: /home/zero/public_html/magento2/1.0.0-beta_v1/app/code/Magestore/Bannerslider/view/frontend/web/js/report/clickbanner.js
 * @Author: zerokool - Nguyen Huu Tien
 * @Email: tien.uet.qh2011@gmail.com
 * @Date:   2015-07-24 08:54:32
 * @Last Modified by:   zero
 * @Last Modified time: 2015-07-27 14:13:22
 */

'use strict';
define([
    'magestore/jquery',
    'magestore/widget'
], function($) {

	$.widget('magestore.clickbanner', {
		options: {
		    url: '',
		    slider_id: '',
		    banner_id: '',
		},

		_create: function() {
			var o = this.options;
			this.element.each(function(index, el) {
				$(el).click(function(event) {
					// event.preventDefault();
					$.ajax({
					    url: o.url,
					    type: 'POST',
					    dataType: 'html',
					    data: {banner_id: o.banner_id, slider_id: o.slider_id},
					}).done(function() {
						console.log("success");
					});

				});
			});

		},
	});
	return $.magestore.clickbanner;
});
