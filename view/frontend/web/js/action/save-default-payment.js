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
        'ko',
        'underscore',
        'Magento_Checkout/js/model/payment-service',
        'Magento_Checkout/js/model/quote',
        'Magento_Checkout/js/action/select-payment-method',
        'Magento_Checkout/js/checkout-data',
        'Magento_Checkout/js/model/payment/method-converter'
    ],
    function (
        $,
        ko,
        _,
        paymentService,
        quote,
        selectPaymentMethodAction,
        checkoutData,
        methodConverter
    ) {
        'use strict';

        paymentService.setPaymentMethods(methodConverter(window.checkoutConfig.paymentMethods));
        return function () {
            if(paymentService.getAvailablePaymentMethods().length > 0){
                var methods = paymentService.getAvailablePaymentMethods();
                var firstMethod = _.last(methods);
                var defaultmethod = window.checkoutConfig.mdosc_default_payment_method;
                var selectedMethod = '';
                if(!_.isUndefined(defaultmethod) || !_.isNull(defaultmethod)) {
                    selectedMethod = _.findWhere(methods,{method:defaultmethod});
                }
                if((_.isUndefined(selectedMethod) || _.isNull(selectedMethod)) &&
                    (!_.isUndefined(firstMethod) || !_.isNull(firstMethod))
                ) {
                    selectedMethod = firstMethod;
                }
                if(selectedMethod && !quote.paymentMethod()){
                    selectPaymentMethodAction(selectedMethod);
                    checkoutData.setSelectedPaymentMethod(selectedMethod.method);
                }
            }
        };
    }
);
