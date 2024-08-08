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
 * @package    Mavenbird_Sorting
 * @author     Mavenbird Team
 * @copyright  Copyright (c) 2018-2024 Mavenbird Technologies Private Limited ( http://mavenbird.com )
 * @license    http://mavenbird.com/Mavenbird-Module-License.txt
 */

define([
    'jquery',
    'jquery/ui'
], function ($) {
    'use strict';

    $.widget('mage.mdDraggableFieldArray', {
        options: {
            rowsContainer: '[data-role=row-container]',
            orderInput: '[data-role=sort-order]'
        },

        /**
         * Initialize widget
         */
        _create: function() {
            var self = this,
                rowsContainer = this.element.find(this.options.rowsContainer);

            rowsContainer.sortable({
                distance: 8,
                tolerance: 'pointer',
                axis: 'y',
                update: function () {
                    rowsContainer.find(self.options.orderInput).each(function (index, element) {
                        $(element).val(index);
                    });
                }
            });
        }
    });

    return $.mage.mdDraggableFieldArray;
});
