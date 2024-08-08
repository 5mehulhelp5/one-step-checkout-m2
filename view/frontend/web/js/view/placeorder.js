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
        'uiComponent',
        'ko',
        'uiRegistry',
        'Mavenbird_OneStepCheckout/js/view/onestepcheckout'
    ],
    function (
        $,
        Component,
        ko,
        registry,
        onestepcheckout
    ) {
        'use strict';
        return Component.extend({
            defaults: {
                template: 'Mavenbird_OneStepCheckout/place-order',
            },
            isBtnVisible: ko.observable(true),
            isCheckoutEnable: ko.pureComputed(function(){
                return onestepcheckout().isCheckoutEnable();
            }),
            initialize: function () {
                this._super();
            },

            prepareToPlaceOrder: function() {
                onestepcheckout().startPlaceOrder();
            }
        });
    }
);
