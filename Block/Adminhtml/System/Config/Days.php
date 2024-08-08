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

use Mavenbird\OneStepCheckout\Model\Config\Source\DayOptions;
use Magento\Framework\View\Element\Context;
use Magento\Framework\View\Element\Html\Select;

class Days extends Select
{
    /**
     * @var DayOptions
     */
    private $dayOptions;

    /**
     * Construct
     *
     * @param Context $context
     * @param DayOptions $dayOptions
     * @param array $data
     */
    public function __construct(
        Context $context,
        DayOptions $dayOptions,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->dayOptions = $dayOptions;
    }

    /**
     * ToHtml
     *
     * @return void
     */
    protected function _toHtml()
    {
        if (!$this->getOptions()) {
            $this->setOptions(
                $this->dayOptions->toOptionArray()
            );
        }
        return parent::_toHtml();
    }

    /**
     * SetInputName
     *
     * @param [type] $value
     * @return void
     */
    public function setInputName($value)
    {
        return $this->setName($value);
    }
}
