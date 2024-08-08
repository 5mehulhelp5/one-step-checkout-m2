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

define(
    [
        'jquery',
        'ko',
        'Magento_Checkout/js/model/totals',
        'uiComponent',
        'Magento_Checkout/js/model/step-navigator',
        'Magento_Checkout/js/model/quote',
    ],
    function ($, ko, totals, Component, stepNavigator, quote) {
        'use strict';
        return Component.extend({
            initialize: function () {
                this._super();
                var self = this;
                totals.isLoading.subscribe(function () {
                    if (totals.isLoading() == true) {
                        self.showOverlay();
                    } else {
                        self.hideOverlay();
                    }
                });
            },
            defaults: {
                template: 'Mavenbird_OneStepCheckout/summary/cart-items'
            },
            totals: totals.totals(),
            getItems: totals.getItems(),
            getItemsQty: function() {
                return parseFloat(this.totals.items_qty);
            },

            showOverlay: function () {
                $('#ajax-loader3').show();
                $('#control_overlay_review').show();
            },

            hideOverlay: function () {
                $('#ajax-loader3').hide();
                $('#control_overlay_review').hide();
            },

            isItemsBlockExpanded: function () {
                return quote.isVirtual() || stepNavigator.isProcessed('shipping');
            }

        });
    }
);
