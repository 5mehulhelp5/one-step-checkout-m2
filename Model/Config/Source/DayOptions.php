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

class DayOptions
{
    /**
     * ToOptionArray
     *
     * @return void
     */
    public function toOptionArray()
    {
        $days = [
            [
                'value' => 0,
                'label' => 'Sunday'
            ],
            [
                'value' => 1,
                'label' => 'Monday'
            ],
            [
                'value' => 2,
                'label' => 'Tuesday'
            ],
            [
                'value' => 3,
                'label' => 'Wednesday'
            ],
            [
                'value' => 4,
                'label' => 'Thursday'
            ],
            [
                'value' => 5,
                'label' => 'Friday'
            ],
            [
                'value' => 6,
                'label' => 'Saturday'
            ],
        ];
        return $days;
    }

    /**
     * GetDateLabelByValue
     *
     * @param [type] $value
     * @return void
     */
    public function getDateLabelByValue($value)
    {
        $time = $this->toOptionArray();
        foreach ($time as $key => $val) {
            if ($val['value'] == $value) {
                return $val['label'];
            }
        }
    }
}
