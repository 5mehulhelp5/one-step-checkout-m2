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
    'Magento_Checkout/js/view/shipping-address/address-renderer/default',
    'Magento_Checkout/js/model/shipping-address/form-popup-state',
    'Magento_Checkout/js/action/set-shipping-information',
    'Magento_Checkout/js/action/select-shipping-address',
    'Magento_Checkout/js/checkout-data',
    'Mavenbird_OneStepCheckout/js/action/reload-shipping-method',
    'Magento_Checkout/js/action/select-billing-address',
    'Magento_Checkout/js/action/set-billing-address',
    'Magento_Ui/js/model/messageList',
    'Mavenbird_OneStepCheckout/js/model/billing-address-state'
], function($, Renderer, formPopUpState,setShippingInformationAction,selectShippingAddressAction,checkoutData,reloadShippingMethod,selectBillingAddress,setBillingAddressAction,globalMessageList,State) {
    'use strict';
    return Renderer.extend({
        isFormPopUpVisible: formPopUpState.isVisible,
        isAddressSameAsShipping: State.sameAsShipping,
        defaults: {
            template: 'Mavenbird_OneStepCheckout/shipping-address/address-renderer/default'
        },
        editAddress: function() {
            this.showForm();
        },
        showForm: function() {
            formPopUpState.isVisible(true);
        },
        selectAddress: function () {
            selectShippingAddressAction(this.address());
            checkoutData.setSelectedShippingAddress(this.address().getKey());
            reloadShippingMethod();
            if (this.isAddressSameAsShipping()) {
                selectBillingAddress(this.address());
                setBillingAddressAction(globalMessageList);
            }
            setShippingInformationAction();
        },
    });
});
