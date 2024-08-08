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
        'ko',
        'Mavenbird_OneStepCheckout/js/view/checkout/summary/extrafee',
        'Magento_Checkout/js/model/quote',
        'Magento_Catalog/js/price-utils',
        'Magento_Checkout/js/model/totals'
    ], function (ko, Component, quote, priceUtils, totals) {
        "use strict";
        return Component.extend({
            totals: quote.getTotals(),

            /**
             * Get formatted price
             * @returns {*|String}
             */
            getValue: function() {
                var price = 0;
                if (this.totals()) {
                    price = totals.getSegment('mdosc_extra_fee').value;
                }
                return this.getFormattedPrice(price);
            },

            isDisplayed: function () {
                return !!(
                    this.totals() &&
                    totals.getSegment('mdosc_extra_fee').value !== null &&
                    totals.getSegment('mdosc_extra_fee').value !== 0
                );
            }
        });
    }
);
