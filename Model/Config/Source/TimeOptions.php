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

class TimeOptions
{
    /**
     * ToOptionArray
     *
     * @return void
     */
    public function toOptionArray()
    {
        $time = [];
        $hoursArray = range(1, 24);
        foreach ($hoursArray as $hour) {
            $AmPm = $hour >= 12 && $hour <= 23 ? 'PM' : 'AM';
            $label = str_pad($hour, 2, '0', STR_PAD_LEFT).':00 '.$AmPm;
            $time[] = [
                'value' => $hour,
                'label' => $label
            ];
        }
        return $time;
    }

    /**
     * GetTimeLabelByValue
     *
     * @param [type] $value
     * @return void
     */
    public function getTimeLabelByValue($value)
    {
        $time = $this->toOptionArray();
        foreach ($time as $key => $val) {
            if ($val['value'] == $value) {
                return $val['label'];
            }
        }
    }
}
