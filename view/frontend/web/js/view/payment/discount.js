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
        'uiComponent',
        'Magento_Checkout/js/model/quote',
        'Magento_Checkout/js/model/full-screen-loader',
        'mage/translate',
        'Magento_SalesRule/js/model/payment/discount-messages',         
        'Mavenbird_OneStepCheckout/js/action/apply-coupon',
        'Mavenbird_OneStepCheckout/js/action/cancel-coupon',
        'Magento_Checkout/js/action/get-payment-information'    
    ],
    function ($, ko, Component, quote, fullScreenLoader, $t, messageContainer, setCouponCodeAction, cancelCouponAction) {
        'use strict';
        var totals = quote.getTotals();
        var couponCode = ko.observable(null);
        if (totals()) {
            couponCode(totals()['coupon_code']);
        }
        var isApplied = ko.observable(couponCode() != null);
        var isLoading = ko.observable(false);
        return Component.extend({
            defaults: {
                template: 'Mavenbird_OneStepCheckout/payment/discount'
            },
            couponCode: couponCode,
            isShowDiscount: ko.observable(true),
            isApplied: isApplied,
            isLoading: isLoading,
            /**
             * Coupon code application procedure
             */
            apply: function() {
                if (this.validate()) {
                    var deferred = $.Deferred();                      
                    fullScreenLoader.startLoader();
                    isLoading(true);
                    setCouponCodeAction(couponCode(), isApplied, isLoading, deferred);
                    var message = $t('Your coupon was successfully applied.');
                    this.processMessage(deferred, message);
                }
            },
            /**
             * Cancel using coupon
             */
            cancel: function() {
                if (this.validate()) {
                    var deferred = $.Deferred();
                    var message = $t('Your coupon was successfully removed.');
                    fullScreenLoader.startLoader();
                    isLoading(true);
                    couponCode('');
                    cancelCouponAction(isApplied, isLoading, deferred);
                    this.processMessage(deferred, message);
                }
            },
            
            processMessage: function(deferred, message) {                
                $.when(deferred).done(function () {
                    fullScreenLoader.stopLoader();
                    messageContainer.addSuccessMessage({'message': message});
                });
                
                $.when(deferred).fail(function () {
                    fullScreenLoader.stopLoader();
                });                
            },
            
            showLoader: function () {
                fullScreenLoader.startLoader();
            },            

            hideLoader: function () {
                fullScreenLoader.stopLoader();
            },


            /**
             * Coupon form validation
             *
             * @returns {boolean}
             */
            validate: function() {
                var form = '#discount-form';
                return $(form).validation() && $(form).validation('isValid');
            }
        });
    }
);
