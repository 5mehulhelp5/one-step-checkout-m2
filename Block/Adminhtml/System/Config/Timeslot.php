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

namespace Mavenbird\OneStepCheckout\Block\Adminhtml\System\Config;

use Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray;
use Magento\Framework\DataObject;

class Timeslot extends AbstractFieldArray
{
    /**
     * @var null
     */
    protected $dayRenderer = null;

    /**
     * @var null
     */
    protected $startTimeRenderer = null;

    /**
     * @var null
     */
    protected $endTimeRenderer = null;

    /**
     * PrepareToRender
     *
     * @return void
     */
    protected function _prepareToRender()
    {
        $this->addColumn(
            'day',
            [
                'label' => __('Day'),
                'renderer' => $this->getDayRenderer(),
                'style' => 'width:266px'
            ]
        );

        $this->addColumn(
            'start_time',
            [
                'label' => __('Start Time'),
                'renderer' => $this->getStartTimeRenderer(),
                'style' => 'width:266px'
            ]
        );

        $this->addColumn(
            'end_time',
            [
                'label' => __('End Time'),
                'renderer' => $this->getEndTimeRenderer(),
                'style' => 'width:266px'
            ]
        );

        $this->_addAfter = false;
        $this->_addButtonLabel = __('Add TimeSlot');
    }

    /**
     * GetDayRenderer
     *
     * @return void
     */
    protected function getDayRenderer()
    {
        if (!$this->dayRenderer) {
            $this->dayRenderer = $this->getLayout()->createBlock(
                Days::class,
                '',
                ['data' => ['is_render_to_js_template' => true]]
            );
        }
        return $this->dayRenderer;
    }

    /**
     * GetStartTimeRenderer
     *
     * @return void
     */
    protected function getStartTimeRenderer()
    {
        if (!$this->startTimeRenderer) {
            $this->startTimeRenderer = $this->getLayout()->createBlock(
                Time::class,
                '',
                ['data' => ['is_render_to_js_template' => true]]
            );
        }
        return $this->startTimeRenderer;
    }

    /**
     * GetEndTimeRenderer
     *
     * @return void
     */
    protected function getEndTimeRenderer()
    {
        if (!$this->endTimeRenderer) {
            $this->endTimeRenderer = $this->getLayout()->createBlock(
                Time::class,
                '',
                ['data' => ['is_render_to_js_template' => true]]
            );
        }
        return $this->endTimeRenderer;
    }

    /**
     * PrepareArrayRow
     *
     * @param DataObject $row
     * @return void
     */
    protected function _prepareArrayRow(DataObject $row)
    {
        $day = $row->getDay();
        $startTime = $row->getStartTime();
        $endTime = $row->getEndTime();
        $options = [];
        if ($day) {
            $options['option_' . $this->getDayRenderer()->calcOptionHash($day)]
                = 'selected="selected"';
        }
        if ($startTime) {
            $options['option_' . $this->getStartTimeRenderer()->calcOptionHash($startTime)]
                = 'selected="selected"';
        }
        if ($endTime) {
            $options['option_' . $this->getEndTimeRenderer()->calcOptionHash($endTime)]
                = 'selected="selected"';
        }
        $row->setData('option_extra_attrs', $options);
    }
}
