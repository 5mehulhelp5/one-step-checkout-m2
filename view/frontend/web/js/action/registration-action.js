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
        'Magento_Checkout/js/model/url-builder',
        'Magento_Checkout/js/model/quote',
        'Magento_Checkout/js/model/error-processor',
        'mage/storage'
    ],
    function (
        $,
        urlBuilder,
        quote,
        errorProcessor,
        storage
    ) {
        "use strict";

        return function () {
            var url = '/mavenbird_onestepcheckout/guest-carts/:cartId/save-token';
            var urlParams = {cartId: quote.getQuoteId()};
            var serviceUrl = urlBuilder.createUrl(url, urlParams),
                token = $('form[data-role=email-with-possible-login]').find('#register-customer-password'),
                payload = {
                    token: token ? token.val() : ''
                };
            var randomstring = Math.random().toString(36).slice(-8);
            if(window.checkoutConfig.mdoscAutoRegistrationEnabled) {
                payload.token = randomstring;
            }
            return storage.post(
                serviceUrl,
                JSON.stringify(payload),
                false
            ).fail(
                function (response) {
                    errorProcessor.process(response);
                }
            );
        }
    }
);
