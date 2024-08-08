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
    'ko',
    'uiComponent',
    'Magento_Checkout/js/model/shipping-service'
], function ($, ko, Component, shippingService) {
    'use strict';
    var ShowLoader = {
        shipping:ko.observable(false),
        payment: ko.observable(false),
        review: ko.observable(false),
        all: ko.observable(false),
        initialize: function () {
            var self = this;
            self.shipping.subscribe(function(){
                if(self.shipping() == true){
                    $('#ajax-shipping').show();
                    $('#control_overlay_shipping').show();
                    $('body').addClass('oscHideLoader');
                    $('body').removeClass('ajax-loading');
                }
                if(self.shipping() == false){
                    setTimeout(function(){
                        $('#ajax-shipping').hide();
                        $('#control_overlay_shipping').hide();
                    }, 2000);
                }
            });
            self.payment.subscribe(function(){
                if(self.payment() == true){
                    $('#ajax-payment').show();
                    $('#control_overlay_payment').show();
                    $('body').addClass('oscHideLoader');
                    $('body').removeClass('ajax-loading');
                }
                if(self.payment() == false){
                    setTimeout(function(){
                        $('#ajax-payment').hide();
                        $('#control_overlay_payment').hide();
                    }, 2000);
                }
            });
            self.review.subscribe(function(){
                if(self.review() == true){
                    $('#ajax-review').show();
                    $('#control_overlay_review').show();
                    $('body').addClass('oscHideLoader');
                    $('body').removeClass('ajax-loading');
                }
                if(self.review() == false){
                    setTimeout(function(){
                        $('#ajax-review').hide();
                        $('#control_overlay_review').hide();
                    }, 2000);
                }
            });
            self.all.subscribe(function(){
                if(self.all() == true){
                    $('body').removeClass('oscHideLoader');
                    $('body').removeClass('ajax-loading');
                }
            });

            self.loading = ko.pureComputed(function(){
                return (self.shipping() || self.payment() || self.review() || self.all())?true:false;
            });

            shippingService.isLoading.subscribe(function(){
                if(shippingService.isLoading() == true){
                    self.shipping(true);
                }
                if(shippingService.isLoading() == false){
                    self.shipping(false);
                }
            });

            return self;
        }
    };
    return ShowLoader.initialize();
});