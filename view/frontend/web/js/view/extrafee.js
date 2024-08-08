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
        'uiComponent',
        'Magento_Checkout/js/model/quote',
        'Magento_Catalog/js/price-utils',
        'Magento_Checkout/js/action/set-shipping-information',
        'Mavenbird_OneStepCheckout/js/action/osc-loader'
    ],
    function(ko, Component, quote, priceUtils, setShippingInformationAction, Loader) {
        'use strict';
        return Component.extend({
            defaults: {
                template: 'Mavenbird_OneStepCheckout/extrafee'
            },
            isExtraFeeEnabled: window.checkoutConfig.mdosc_extrafee_enabled,
            extrafeeCheckboxTitle: window.checkoutConfig.mdosc_extrafee_checkbox_label,
            initObservable: function () {
                var parent = this._super();
                parent.observe({
                    extrafeeChecked: ko.observable(false)
                });

                this.extrafeeChecked.subscribe(function (newValue) {
                    Loader.review(true);
                    setShippingInformationAction().always(
                        function () {
                            Loader.review(false);
                        }
                    );
                });
                return this;
            }
        });
    }
);
