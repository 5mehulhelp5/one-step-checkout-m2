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
    'uiRegistry',
    'ko',
    'Magento_Checkout/js/view/form/element/email',
    'Mavenbird_OneStepCheckout/js/model/login-form-validator',
    'Magento_Customer/js/customer-data',
    'Magento_Checkout/js/model/quote',
    'Magento_Checkout/js/checkout-data',
    'mage/validation',
    'Magento_Ui/js/lib/view/utils/async',
], function ($, registry, ko, Component, loginFormValidator, customerData, quote, checkoutData) {
    'use strict';

    /**
     * Get Amazon customer email
     */
    function getAmazonCustomerEmail() {
        // jscs:disable requireCamelCaseOrUpperCaseIdentifiers
        if (window.checkoutConfig.hasOwnProperty('amazonLogin') &&
            typeof window.checkoutConfig.amazonLogin.amazon_customer_email === 'string'
        ) {
            return window.checkoutConfig.amazonLogin.amazon_customer_email;
        }
        // jscs:enable requireCamelCaseOrUpperCaseIdentifiers

        return '';
    }

    return Component.extend({
        defaults: {
            email: checkoutData.getInputFieldEmailValue() || getAmazonCustomerEmail(),
            template: 'Mavenbird_OneStepCheckout/form/element/email'
        },
        isRegisterPasswordVisible: ko.observable(false),
        isRegister: ko.observable(false),
        isPassword: ko.observable(false),

        /**
         * Init email validator
         */
        initialize: function () {
            this._super();
            //this._super().observe({isRegister: ko.observable(false)});
            if (this.email()) {

                if ($.validator.methods['validate-email'].call(this, this.email())) {
                    quote.guestEmail = this.email();
                    checkoutData.setValidatedEmailValue(this.email());
                }
                checkoutData.setInputFieldEmailValue(this.email());
            }

            this.isRegister.subscribe(function (newValue) {
                if(newValue){
                    this.isRegisterPasswordVisible(true);
                }else{
                    this.isRegisterPasswordVisible(false);
                }
            }.bind(this));

            return this;
        },

        initObservable: function () {
            this._super();
            return this;
        },

        isPasswordSet: function (element) {
            this.isPassword(!!element.value);
        },

        getRequiredPasswordCharacter: function () {
            return parseInt(registry.get("checkout.steps.shipping-step.shippingAddress.customer-email").requiredPasswordCharacter);
        },

        getMinimumPasswordLength: function () {
            return parseInt(registry.get("checkout.steps.shipping-step.shippingAddress.customer-email").minimumPasswordLength);
        },

        isRegisterVisible: function () {
            var flag = false;
            this.isPasswordVisible() ? flag = false : flag = true;
            return flag;
        },

        // isRegister: function () {
        //     this.isRegisterPasswordVisible(true);
        // }
    });
});
