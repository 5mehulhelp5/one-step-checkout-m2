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
    'Magento_GiftMessage/js/model/url-builder',
    'mage/storage',
    'Magento_Ui/js/model/messageList',
    'Magento_Checkout/js/model/error-processor',
    'mage/url',
    'Magento_Checkout/js/model/quote',
    'underscore',
    'mage/translate',
    'Mavenbird_OneStepCheckout/js/action/osc-loader'
], function (urlBuilder, storage, messageList, errorProcessor, url, quote, _, $t, Loader) {
    'use strict';

    return function (giftMessage, remove) {
        var serviceUrl;
        Loader.all(true);
        url.setBaseUrl(giftMessage.getConfigValue('baseUrl'));

        if (giftMessage.getConfigValue('isCustomerLoggedIn')) {
            serviceUrl = urlBuilder.createUrl('/carts/mine/gift-message', {});

            if (giftMessage.itemId !== 'orderLevel') { //eslint-disable-line eqeqeq
                serviceUrl = urlBuilder.createUrl('/carts/mine/gift-message/:itemId', {
                    itemId: giftMessage.itemId
                });
            }
        } else {
            serviceUrl = urlBuilder.createUrl('/guest-carts/:cartId/gift-message', {
                cartId: quote.getQuoteId()
            });

            if (giftMessage.itemId !== 'orderLevel') { //eslint-disable-line eqeqeq
                serviceUrl = urlBuilder.createUrl(
                    '/guest-carts/:cartId/gift-message/:itemId',
                    {
                        cartId: quote.getQuoteId(), itemId: giftMessage.itemId
                    }
                );
            }
        }
        messageList.clear();
        storage.post(
            serviceUrl,
            JSON.stringify({
                'gift_message': giftMessage.getSubmitParams(remove)
            })
        ).done(function (response) {
            giftMessage.reset();
            if(remove) {
                messageList.addSuccessMessage({message: $t('Gift messages has been removed')});
            } else {
                messageList.addSuccessMessage({message: $t('Gift messages has been successfully updated')});
            }
            Loader.all(false);
        }).fail(function (response) {
            errorProcessor.process(response);
            Loader.all(false);
        });
    };
});
