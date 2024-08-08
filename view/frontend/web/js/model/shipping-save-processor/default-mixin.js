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
        'jquery',
        'mage/utils/wrapper',
        'underscore',
        'ko',
        'Magento_Checkout/js/model/quote',
        'Magento_Checkout/js/model/resource-url-manager',
        'mage/storage',
        'Magento_Checkout/js/model/payment-service',
        'Magento_Checkout/js/model/payment/method-converter',
        'Magento_Checkout/js/model/error-processor',
        'Magento_Checkout/js/model/full-screen-loader',
        'Magento_Checkout/js/action/select-billing-address',
        'Magento_Checkout/js/action/create-shipping-address',
        'Magento_Checkout/js/action/select-shipping-address',
        'Magento_Checkout/js/checkout-data',
        'uiRegistry',
        'Mavenbird_OneStepCheckout/js/model/billing-address-state'
    ], function (
        $,
        wrapper,
        _,
        ko,
        quote,
        resourceUrlManager,
        storage,
        paymentService,
        methodConverter,
        errorProcessor,
        fullScreenLoader,
        selectBillingAddressAction,
        createShippingAddress,
        selectShippingAddress,
        checkoutData,
        registry,
        BillingAddressState
    ) {
    'use strict';

    return function (payloadExtender) {
        return wrapper.wrap(payloadExtender, function (originalFunction, payload) {
            if (!quote.billingAddress() || BillingAddressState.sameAsShipping() === true) {
                selectBillingAddressAction(quote.shippingAddress());
            }

            payload = originalFunction(payload);

            _.extend(payload.addressInformation.extension_attributes, {
                'mdosc_extra_fee_checked': $('[name="extrafee_checkbox"]').prop("checked") ? 'checked' : 'unchecked',
            });

            return payload;
        });
    };
});