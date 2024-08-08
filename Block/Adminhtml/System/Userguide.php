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

namespace Mavenbird\OneStepCheckout\Block\Adminhtml\System;

class Userguide extends \Magento\Config\Block\System\Config\Form\Fieldset
{

    /**
     * PrepareLayout
     *
     * @return void
     */
    protected function _prepareLayout()
    {
        $this->addChild('position_block', 'Mavenbird\OneStepCheckout\Block\Adminhtml\Widget\System\Config\Userguide');

        return parent::_prepareLayout();
    }

    /**
     * Render
     *
     * @param \Magento\Framework\Data\Form\Element\AbstractElement $element
     * @return void
     */
    public function render(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
        return $this->getChildHtml('position_block');
    }
}
