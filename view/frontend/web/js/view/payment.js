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
        "underscore",
        'ko',
        'uiRegistry',
        'Magento_Checkout/js/model/payment-service',
        'Magento_Checkout/js/model/payment/method-converter',
        'mage/translate',
        'Magento_Checkout/js/view/payment',
        'Magento_Checkout/js/model/quote',
        'Magento_Checkout/js/model/payment/method-list',
        'Magento_Checkout/js/action/get-payment-information',
        'Mavenbird_OneStepCheckout/js/action/save-default-payment',
        'Mavenbird_OneStepCheckout/js/action/osc-loader'
    ],
    function (
        $,
        _,
        ko,
        registry,
        paymentService,
        methodConverter,
        $t,
        Payment,
        quote,
        methodList,
        getPaymentInformation,
        saveDefaultPayment,
        loader
    ) {
        'use strict';

        /** Set payment methods to collection */
        paymentService.setPaymentMethods(methodConverter(window.checkoutConfig.paymentMethods));

        return Payment.extend({
            defaults: {
                template: 'Mavenbird_OneStepCheckout/payment',
                activeMethod: ''
            },
            isVisible: ko.observable(quote.isVirtual()),
            quoteIsVirtual: quote.isVirtual(),
            initialize: function () {
                loader.payment(true);
                this._super();
                this.navigate();
                methodList.subscribe(function () {
                    saveDefaultPayment();
                });
                loader.payment(false);
                return this;
            },

            /**
             * Navigate method.
             */
            navigate: function () {
                var self = this;
                loader.payment(true);
                getPaymentInformation().done(function () {
                    self.isVisible(true);
                    loader.payment(true);
                }).fail(function () {
                    loader.payment(false);
                });
            },
            getSequence: function() {
                return parseInt(registry.get("checkout.steps.billing-step").sortOrder) + 1;
            }
        });
    }
);
