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
    'mage/utils/wrapper',
    'jquery'
], function (wrapper, $) {
    'use strict';

    return function (agreementsAssignerAction) {
        return wrapper.wrap(agreementsAssignerAction, function (originalAction, paymentData) {
            originalAction(paymentData);
            if(window.checkoutConfig.agreement_block === 'sidebar') {
                var agreementForm,
                    agreementData,
                    agreementIds;

                agreementForm = $('div[data-role=checkout-agreements] input');
                agreementData = agreementForm.serializeArray();
                agreementIds = [];

                agreementData.forEach(function (item) {
                    agreementIds.push(item.value);
                });

                if (paymentData['extension_attributes'] === undefined) {
                    paymentData['extension_attributes'] = {};
                }

                paymentData['extension_attributes']['agreement_ids'] = agreementIds;
                return paymentData;
            }
        });
    };
});