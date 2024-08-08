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
        'Magento_Checkout/js/model/quote',
        'Magento_Checkout/js/model/shipping-rate-processor/new-address',
        'Magento_Checkout/js/model/shipping-rate-processor/customer-address'
    ],
    function($, Component, ko, quote, defaultProcessor, customerAddressProcessor) {
        'use strict';

        return Component.extend({
            processors: [],
            stop: ko.observable(false),
            initialize: function () {
                this._super();
                var self = this;
                self.processors.default =  defaultProcessor;
                self.processors['customer-address'] = customerAddressProcessor;

                quote.shippingAddress.subscribe(function () {
                    if(self.stop() == false){                  
                        var type = quote.shippingAddress().getType();
                        if (self.processors[type]) {
                            self.processors[type].getRates(quote.shippingAddress());
                        } else {
                            self.processors.default.getRates(quote.shippingAddress());
                        }
                    }
                });
            }
        });
    }
);
