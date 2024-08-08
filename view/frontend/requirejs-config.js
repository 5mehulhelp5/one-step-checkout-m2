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

var patch = window.magentoVersion_osc_patch;
var config;
config = {
    map: {
        '*': {
            'Magento_Checkout/js/view/billing-address': 'Mavenbird_OneStepCheckout/js/view/billing-address',
            'Magento_Checkout/template/shipping-address/address-renderer/default.html': 'Mavenbird_OneStepCheckout/template/shipping-address/address-renderer/default.html'
            // 'Magento_Checkout/js/model/shipping-save-processor/default': 'Mavenbird_OneStepCheckout/js/model/shipping-save-processor/default'
        }
    },
    config: {
        'mixins': {
            'Magento_Checkout/js/view/shipping-address/address-renderer/default': {
                'Mavenbird_OneStepCheckout/js/view/shipping-address/address-renderer/default-mixins': true
            },
            'Magento_CheckoutAgreements/js/model/agreements-assigner': {
                'Mavenbird_OneStepCheckout/js/model/agreements-assigner-mixin': true
            },
            'Magento_Checkout/js/action/place-order': {
                'Mavenbird_OneStepCheckout/js/model/place-order-mixin': true
            },
            'Magento_Checkout/js/sidebar': {
                'Mavenbird_OneStepCheckout/js/action/sidebar-mixins': true
            },
            'Magento_Checkout/js/model/shipping-save-processor/payload-extender': {
                'Mavenbird_OneStepCheckout/js/model/shipping-save-processor/default-mixin': true
            },
            'Magento_Checkout/js/action/set-payment-information': {
                'Mavenbird_OneStepCheckout/js/action/set-payment-information-mixin': true
            },
            'Magento_Paypal/js/view/payment/method-renderer/in-context/checkout-express': {
                'Mavenbird_OneStepCheckout/js/view/payment/method-renderer/in-context/checkout-express-mixin': true
            }
        }
    }
};
if(patch === '232') {
    config['map']['*']['Magento_Checkout/js/view/billing-address/list'] = 'Mavenbird_OneStepCheckout/js/view/billing-address/list';
}