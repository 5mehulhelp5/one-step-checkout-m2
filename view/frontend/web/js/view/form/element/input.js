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

/*browser:true*/
/*global define*/
define([
    'jquery',
    'Magento_Ui/js/form/element/abstract',
    'Mavenbird_OneStepCheckout/js/action/validate-shipping-info',
    'Mavenbird_OneStepCheckout/js/action/save-shipping-address'
], function ($, abstract,ValidateShippingInfo,SaveAddressBeforePlaceOrder) {
    'use strict';

    return abstract.extend({
        saveShippingAddress: function(){
            if(ValidateShippingInfo()){
                SaveAddressBeforePlaceOrder();
            }
        }
    });
});
