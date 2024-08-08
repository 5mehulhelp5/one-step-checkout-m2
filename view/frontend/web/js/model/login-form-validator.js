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

efine(
    [
        'Magento_Ui/js/lib/validation/validator',
        'jquery',
        'jquery/ui',
        'jquery/validate',
        'mage/translate',
        'Magento_Customer/js/model/customer'
    ],
    function (validator, $, ui, validate, $t, customer) {
        'use strict';

        return {
            /**
             * Validate Login Form on checkout if available
             *
             * @returns {Boolean}
             */
            validate: function () {
                var loginForm = 'form[data-role=email-with-possible-login]',
                    password = $(loginForm).find('#register-customer-password');

                if (customer.isLoggedIn()) {
                    return true;
                }
                return $(loginForm).validation() && $(loginForm).validation('isValid');
                // if (password.val()) {
                //     return $(loginForm).validation() && $(loginForm).validation('isValid');
                // }
            }
        };
    }
);
