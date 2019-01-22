define(
    [
		'jquery',
        'Magento_Ui/js/modal/modal',
        'mage/translate',
        'ko',
        'uiComponent'
    ],
    function($, modal, $t, ko, Component) {
        'use strict';
        return Component.extend({
            defaults: {
                template: 'Rokanthemes_OpCheckout/ost-terms-and-conditions'
            },
			
			labelOstTermsAndConditions: ko.observable(window.checkoutConfig.terms_and_con_title),
			
			contentOstTermsAndConditions: ko.observable(window.checkoutConfig.terms_and_con_terms_content),
			
			showContentOpCheckoutContent: function(){
				$('#terms_and_conditions_checkbox').prop('checked', true);
				var options = {
                    'type': 'popup',
					'title': window.checkoutConfig.terms_and_con_title,
                    'modalClass': 'agreements-modal-opcheckout',
                    'responsive': true,
                    'innerScroll': true,
                    'trigger': '.show-modal',
                    'buttons': [
                        {
                            text: $t('Close'),
                            class: 'action secondary action-hide-popup',
                            click: function() {
                                this.closeModal();
                            }
                        }
                    ]
                };
				var popup = modal(options, $('#checkout-agreements-modal-opcheckout'));
				$('#checkout-agreements-modal-opcheckout').modal('openModal');
			},
			
            isShowTermsandConditions: ko.observable(window.checkoutConfig.terms_enable)
        });
    }
);
