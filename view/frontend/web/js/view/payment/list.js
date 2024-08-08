/**
 * Mavenbird Technologies Private Limited
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://mavenbird.com/Mavenbird-Module-License.txt
 *
 * =================================================================
 *
 * @category   Mavenbird
 * @package    Mavenbird_OneStepCheckout
 * @author     Mavenbird Team
 * @copyright  Copyright (c) 2018-2024 Mavenbird Technologies Private Limited ( http://mavenbird.com )
 * @license    http://mavenbird.com/Mavenbird-Module-License.txt
 */

define([
    'underscore',
    'ko',
    'mageUtils',
    'uiComponent',
    'Magento_Checkout/js/model/payment/method-list',
    'Magento_Checkout/js/model/payment/renderer-list',
    'uiLayout',
    'Magento_Checkout/js/model/checkout-data-resolver',
    'mage/translate',
    'uiRegistry',
    'Magento_Checkout/js/view/payment/list',
    'Amazon_Payment/js/view/payment/list'
], function (_, ko, utils, Component, paymentMethods, rendererList, layout, checkoutDataResolver, $t, registry, List, AmazonList) {
    'use strict';

        return List.extend({
            defaults: {
                template: 'Mavenbird_OneStepCheckout/payment-methods/list'
            },
            /**
             * Returns payment group title
             *
             * @param {Object} group
             * @returns {String}
             */
            getGroupTitle: function (group) {
                var title = group().title;

                if (group().isDefault() && this.paymentGroupsList().length > 1) {
                    title = this.defaultGroupTitle;
                }
                return title;
            },

            getPaymentListTitle: function () {
                return window.checkoutConfig.payment_step_config_label ?
                    window.checkoutConfig.payment_step_config_label :
                    'Payment Methods';
            },

            canDisplayWallet: function (group){
                if(group().index === "vaultGroup"){
                    return false;
                }
                return true;
            },

            getSequence: function() {
                return parseInt(registry.get("checkout.steps.billing-step").sortOrder) + 1;
            }
        });
    }
);
