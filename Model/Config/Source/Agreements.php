<?php
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

namespace Mavenbird\OneStepCheckout\Model\Config\Source;

class Agreements
{
    /**
     * ToOptionArray
     *
     * @return void
     */
    public function toOptionArray()
    {
        $responseArray = [
            [
                'label' => 'Below Payment Method',
                'value' => 'payment'
            ],
            [
                'label' => 'Before Place Order Button',
                'value' => 'sidebar'
            ]
        ];
        return $responseArray;
    }
}
