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
    'uiRegistry',
    'Mavenbird_OneStepCheckout/js/action/save-information',
    'Mavenbird_OneStepCheckout/js/model/login-form-validator',
    'Mavenbird_OneStepCheckout/js/action/registration-action'
], function (
    $,
    wrapper,
    registry,
    saveAdditionalInfo,
    loginFormValidator,
    RegistrationAction
) {
    'use strict';

    return function (setPaymentInformationAction) {
        return wrapper.wrap(setPaymentInformationAction, function (originalAction, messageContainer, paymentData) {
            var shippingAddressComponent = registry.get('checkout.steps.shippingMethods');
            if(shippingAddressComponent) {
                if (shippingAddressComponent.validateShippingInformation() === false) {
                    return false;
                }
            }
            if(window.checkoutConfig.save_additional_info_from_payment === true) {
                if(window.checkoutConfig.mdoscAutoRegistrationEnabled) {
                    RegistrationAction();
                }
                if(window.checkoutConfig.mdoscRegistrationEnabled &&
                    registry.get("checkout.steps.shipping-step.shippingAddress.customer-email").isRegisterPasswordVisible()
                ) {
                    if(loginFormValidator.validate()) {
                        RegistrationAction();
                    } else {
                        return false;
                    }
                }
                saveAdditionalInfo();
            }
            return originalAction(messageContainer, paymentData);
        });
    };
});
