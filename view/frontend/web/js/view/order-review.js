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
    'uiComponent',
    "underscore",
    'ko',
    'uiRegistry',
    'Mavenbird_OneStepCheckout/js/action/osc-loader'
], function (
    $,
    component,
    _,
    ko,
    registry,
    loader
) {
    'use strict';
    return component.extend({
        initialize: function () {
            loader.review(true);
            this._super();
            loader.review(false);
            return this;
        },
        getSequence: function() {
            return parseInt(registry.get("checkout.steps.order-review").sortOrder) + 1;
        }
    });
});