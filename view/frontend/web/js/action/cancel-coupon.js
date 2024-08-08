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
        'Magento_Checkout/js/model/quote',
        'Magento_Checkout/js/model/resource-url-manager',
        'Magento_Checkout/js/model/error-processor',
        'Magento_SalesRule/js/model/payment/discount-messages',
        'mage/storage',
        'Magento_Checkout/js/action/get-payment-information',
        'Magento_Checkout/js/model/totals',
        'mage/translate',
        'Mavenbird_OneStepCheckout/js/action/reload-shipping-method',
        'Mavenbird_OneStepCheckout/js/action/osc-loader'
    ],
    function ($, quote, urlManager, errorProcessor, messageContainer, storage, getPaymentInformationAction, totals, $t, reloadShippingMethod, Loader) {
        'use strict';

        return function (isApplied, isLoading, deferred) {
            Loader.all(true);
            var quoteId = quote.getQuoteId(),
                url = urlManager.getCancelCouponUrl(quoteId),
                message = $t('Your coupon was successfully removed.');
            messageContainer.clear();
            deferred = deferred || $.Deferred();

            return storage.delete(
                url,
                false
            ).done(
                function () {
                    getPaymentInformationAction(deferred);
                    reloadShippingMethod();
                    $.when(deferred).done(function () {
                        isApplied(false);
                        deferred.resolve();
                    });
                }
            ).fail(
                function (response) {
                    totals.isLoading(false);
                    errorProcessor.process(response, messageContainer);
                }
            ).always(
                function () {
                    isLoading(false);
                    Loader.all(false);
                }
            );
        };
    }
);
