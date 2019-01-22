/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

var config = {
    "map": {
        '*': {
			'productQuickview': 'Rokanthemes_QuickView/js/quickview'
        },
    },
	"shim": {
		"quickview/cloudzoom": ["jquery"],
		"quickview/bxslider": ["jquery"]
	},
	'paths': {
		'quickview/cloudzoom': 'Rokanthemes_QuickView/js/cloud-zoom',
        "quickview/bxslider": "Rokanthemes_QuickView/js/jquery.bxslider"
    }
};
