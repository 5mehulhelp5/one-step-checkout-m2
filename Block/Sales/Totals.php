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

namespace Mavenbird\OneStepCheckout\Block\Sales;

use Magento\Directory\Model\Currency;
use Magento\Framework\DataObject;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Mavenbird\OneStepCheckout\Helper\Data;

class Totals extends Template
{

    /**
     * @var Data
     */
    protected $helper;

    /**
     * @var Currency
     */
    protected $currency;

    /**
     * Construct
     *
     * @param Context $context
     * @param Data $helper
     * @param Currency $currency
     * @param array $data
     */
    public function __construct(
        Context $context,
        Data $helper,
        Currency $currency,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->helper = $helper;
        $this->currency = $currency;
    }

    /**
     * GetOrder
     *
     * @return void
     */
    public function getOrder()
    {
        return $this->getParentBlock()->getOrder();
    }

    /**
     * GetSource
     *
     * @return void
     */
    public function getSource()
    {
        return $this->getParentBlock()->getSource();
    }

    /**
     * GetCurrencySymbol
     *
     * @return void
     */
    public function getCurrencySymbol()
    {
        return $this->currency->getCurrencySymbol();
    }

    /**
     * InitTotals
     *
     * @return void
     */
    public function initTotals()
    {
        $this->getParentBlock();
        $this->getOrder();
        $this->getSource();
        if ($this->getSource()->getMdoscExtraFee() <= 0) {
            return $this;
        }
        $total = new DataObject(
            [
                'code' => 'mdosc_extra_fee',
                'value' => $this->getSource()->getMdoscExtraFee(),
                'label' => $this->helper->getExtraFeeLabel(),
            ]
        );
        $this->getParentBlock()->addTotalBefore($total, 'grand_total');

        return $this;
    }
}
