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
        'jquery',
        'Magento_Checkout/js/model/quote',
        'Magento_Checkout/js/model/resource-url-manager',
        'Magento_Checkout/js/model/error-processor',
        'Magento_SalesRule/js/model/payment/discount-messages',
        'mage/storage',
        'mage/translate',
        'Magento_Checkout/js/action/get-payment-information',
        'Magento_Checkout/js/model/totals',
        'Mavenbird_OneStepCheckout/js/action/reload-shipping-method',
        'Mavenbird_OneStepCheckout/js/action/osc-loader'
    ],
    function (
        ko,
        $,
        quote,
        urlManager,
        errorProcessor,
        messageContainer,
        storage,
        $t,
        getPaymentInformationAction,
        totals,
        reloadShippingMethod,
        Loader
    ) {
        'use strict';
        return function (couponCode, isApplied, isLoading, deferred) {
            Loader.all(true);
            var quoteId = quote.getQuoteId();
            var url = urlManager.getApplyCouponUrl(couponCode, quoteId);
            deferred = deferred || $.Deferred();
            return storage.put(
                url,
                {},
                false
            ).done(
                function (response) {
                    if (response) {
                        isLoading(false);
                        isApplied(true);
                        getPaymentInformationAction(deferred);
                        reloadShippingMethod();
                        $.when(deferred).done(function () {
                            deferred.resolve();
                        });                        
                        
                    }
                    Loader.all(false);
                }
            ).fail(
                function (response) {
                    isLoading(false);
                    totals.isLoading(false);
                    errorProcessor.process(response, messageContainer);
                    deferred.reject();
                    Loader.all(false);
                }
            );
        };
    }
);
