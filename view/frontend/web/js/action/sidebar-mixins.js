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
    'jquery'
], function ($) {
    'use strict';

    return function (widget) {
        $.widget('mage.sidebar', widget, {
            _removeItemAfter: function (elem) {
                this._super(elem);
                if (window.location.href.split('#')[0] === window.checkout.checkoutUrl) {
                    window.location.reload(false);
                }
            },
            _updateItemQtyAfter: function (elem) {
                this._super(elem);
                if (window.location.href.split('#')[0] === window.checkout.checkoutUrl) {
                    window.location.reload(false);
                }
            }
        });
        return $.mage.sidebar;
    }
});
