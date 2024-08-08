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
        'ko',
        'jquery',
        'mage/storage'
    ],
    function (ko, $, storage) {
        'use strict';
        return function () {
            var deferred = $.Deferred();
            var newsletter = '';
            var newsletterElem = $('#newsletter_subscriber_checkbox');
            var commentElem = $('#order_comments');
            var deliveryDateElem = $('[name="md_osc_delivery_date"]');
            var deliveryTimeElem = $('[name="md_osc_delivery_time"]');
            var deliveryCommentElem = $('[name="md_osc_delivery_comment"]');

            if (newsletterElem.length > 0) {
                newsletter = newsletterElem.attr('checked') === 'checked' > 0 ? 1 : 0;
            }
            var orderComments = commentElem.length > 0 ? commentElem.val() : '';
            var deliveryDate = deliveryDateElem.length > 0 ? deliveryDateElem.val() : '';
            var deliveryTime = deliveryTimeElem.length > 0 ? deliveryTimeElem.val() : '';
            var deliveryComment = deliveryCommentElem.length > 0 ? deliveryCommentElem.val() : '';
            
            var params = {
                'onestepcheckout_newsletter': newsletter,
                'onestepcheckout_order_comments': orderComments,
                'md_osc_delivery_date': deliveryDate,
                'md_osc_delivery_time': deliveryTime,
                'md_osc_delivery_comment': deliveryComment
            };
            storage.post(
                'onestepcheckout/order/saveAdditionalInfo',
                JSON.stringify(params),
                false
            ).done(
                function (result) {}
            ).fail(
                function (result) {}
            ).always(
                function (result) {
                    deferred.resolve(result);
                }
            );
            return deferred;
        };
    }
);
