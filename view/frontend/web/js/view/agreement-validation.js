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
    'uiComponent',
    'Magento_Checkout/js/model/payment/additional-validators',
    'Mavenbird_OneStepCheckout/js/model/agreement-validator'
], function (Component, additionalValidators, agreementValidator) {
    'use strict';

    additionalValidators.registerValidator(agreementValidator);

    return Component.extend({});
});
