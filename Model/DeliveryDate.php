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

namespace Mavenbird\OneStepCheckout\Model;

class DeliveryDate
{
    /**
     * @var \Mavenbird\OneStepCheckout\Helper\Data
     */
    private $oscHelper;

    /**
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    protected $date;

    /**
     * @var Config\Source\TimeOptions
     */
    protected $timeConfig;

    /**
     * @var Config\Source\DayOptions
     */
    protected $dayConfig;

    /**
     * Construct
     *
     * @param \Mavenbird\OneStepCheckout\Helper\Data $oscHelper
     * @param \Magento\Framework\Stdlib\DateTime\DateTime $date
     * @param \Mavenbird\OneStepCheckout\Model\Config\Source\TimeOptions $timeConfig
     * @param \Mavenbird\OneStepCheckout\Model\Config\Source\DayOptions $dayConfig
     */
    public function __construct(
        \Mavenbird\OneStepCheckout\Helper\Data $oscHelper,
        \Magento\Framework\Stdlib\DateTime\DateTime $date,
        \Mavenbird\OneStepCheckout\Model\Config\Source\TimeOptions $timeConfig,
        \Mavenbird\OneStepCheckout\Model\Config\Source\DayOptions $dayConfig
    ) {
        $this->oscHelper = $oscHelper;
        $this->date = $date;
        $this->timeConfig = $timeConfig;
        $this->dayConfig = $dayConfig;
    }

    /**
     * GetDeliveryTimeSlot
     *
     * @return void
     */
    public function getDeliveryTimeSlot()
    {
        $time = [];
        $timeSlot = $this->oscHelper->getDeliveryTimeSlot();
        $minInterval = (int) $this->oscHelper->getDeliveryMinInterval();
        $minDate = $this->date->gmtDate('d-m-Y', strtotime("+".$minInterval."days"));
        $maxInterval = (int) $this->oscHelper->getDeliveryMaxInterval();
        $countDates = 0;
        while ($countDates < $maxInterval) {
            $NewDate = $this->date->gmtDate('d-m-Y', strtotime($minDate."+".$countDates." days"));
            $dayofweek = date('w', strtotime($NewDate));
            if (in_array($dayofweek, array_column($timeSlot, 'day'))) {
                $day = $this->dayConfig->getDateLabelByValue($dayofweek);
                $time[strtotime($NewDate)]['day'] = $day;
                $time[strtotime($NewDate)]['date'] = $NewDate;
                $time[strtotime($NewDate)]['unix_date'] = strtotime($NewDate);
                $time[strtotime($NewDate)]['time'] = $this->getTimeSlotByDay($timeSlot, $dayofweek);
            }
            $countDates += 1;
        }
        return $time;
    }

    /**
     * GetTimeSlotByDay
     *
     * @param [type] $timeSlot
     * @param [type] $day
     * @return void
     */
    private function getTimeSlotByDay($timeSlot, $day)
    {
        $time = [];
        $filterBy = $day;
        $filterDays = array_filter($timeSlot, function ($var) use ($filterBy) {
            return ($var['day'] == $filterBy);
        });
        foreach ($filterDays as $filterDay) {
            $startTime = $this->timeConfig->getTimeLabelByValue($filterDay['start_time']);
            $endTime = $this->timeConfig->getTimeLabelByValue($filterDay['end_time']);
            $time[] = $startTime.' - '.$endTime;
        }
        return $time;
    }
}
