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

namespace Mavenbird\OneStepCheckout\Model\Quote\Total;

use Magento\Framework\Phrase;
use Magento\Quote\Api\Data\ShippingAssignmentInterface;
use Magento\Quote\Model\Quote;
use Magento\Quote\Model\Quote\Address\Total;
use Magento\Quote\Model\Quote\Address\Total\AbstractTotal;
use Mavenbird\OneStepCheckout\Helper\Data as OscHelper;

class ExtraFee extends AbstractTotal
{
    /**
     * @var OscHelper
     */
    protected $helper;

    /**
     * Construct
     *
     * @param OscHelper $helper
     */
    public function __construct(
        OscHelper $helper
    ) {
        $this->helper = $helper;
    }

    /**
     * Collect
     *
     * @param Quote $quote
     * @param ShippingAssignmentInterface $shippingAssignment
     * @param Total $total
     * @return void
     */
    public function collect(
        Quote $quote,
        ShippingAssignmentInterface $shippingAssignment,
        Total $total
    ) {
        parent::collect($quote, $shippingAssignment, $total);

        if (!count($shippingAssignment->getItems())) {
            return $this;
        }

        if (!$this->helper->isExtraFeeEnabled()) {
            return $this;
        }

        if ($quote->getMdoscExtraFeeChecked() == 'checked') {

            $feeAmount = $this->helper->getExtraFee();

            $total->setTotalAmount('mdosc_extra_fee', $feeAmount);
            $total->setBaseTotalAmount('mdosc_extra_fee', $feeAmount);

            $total->setMdoscExtraFee($feeAmount);
            $total->setBaseMdoscExtraFee($feeAmount);

            $quote->setMdoscExtraFee($feeAmount);
            $quote->setBaseMdoscExtraFee($feeAmount);

            $quote->setGrandTotal($total->getGrandTotal() + $feeAmount);
            $quote->setBaseGrandTotal($total->getBaseGrandTotal() + $feeAmount);
        } else {
            $total->setMdoscExtraFee(0);
            $total->setBaseMdoscExtraFee(0);

            $quote->setMdoscExtraFee(0);
            $quote->setBaseMdoscExtraFee(0);
        }

        return $this;
    }

    /**
     * Assignsubtotalamountandlabeltoaddressobject
     *
     * @param Quote $quote
     * @param Total $total
     *
     * @return array
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function fetch(
        Quote $quote,
        Total $total
    ) {
        if ($quote->getMdoscExtraFeeChecked() == 'checked') {
            $feeAmount = $this->helper->getExtraFee();
        } else {
            $feeAmount = 0.00;
        }

        $result = [
            'code' => 'mdosc_extra_fee',
            'title' => $this->getLabel(),
            'value' => $feeAmount
        ];

        return $result;
    }

    /**
     * Getlabel
     *
     * @return Phrase
     */
    public function getLabel()
    {
        return $this->helper->getExtraFeeLabel();
    }
}
