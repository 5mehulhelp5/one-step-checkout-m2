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
    'Magento_Paypal/js/in-context/express-checkout-smart-buttons'
], function (checkoutSmartButtons) {
    'use strict';
    window.paypalElement = false;
    return function(target){
        target.renderPayPalButtons = function (element) {
            if (window.paypalElement == false) {
                window.paypalElement = element;
            }
            if (typeof window.checkoutConfig === "undefined") {
                checkoutSmartButtons(this.prepareClientConfig(), element);
            }
        };
        return target;
    };
});