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
    'Magento_Checkout/js/action/select-billing-address',
    'Magento_Checkout/js/action/set-billing-address',
    'Magento_Ui/js/model/messageList',
    'Mavenbird_OneStepCheckout/js/model/billing-address-state',
    'Magento_Checkout/js/action/set-shipping-information'
], function (selectBillingAddress,setBillingAddressAction,globalMessageList,State,setShippingInformationAction) {
    'use strict';

    return function (Component) {
        return Component.extend({
            isAddressSameAsShipping: State.sameAsShipping,
            selectAddress: function () {
                this._super();
                if (this.isAddressSameAsShipping()) {
                    selectBillingAddress(this.address());
                    setBillingAddressAction(globalMessageList);
                }
                setShippingInformationAction();
            }
        });
    }
});
