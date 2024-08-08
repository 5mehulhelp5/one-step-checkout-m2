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

namespace Mavenbird\OneStepCheckout\Model\Invoice\Total;

use Magento\Sales\Model\Order\Invoice;
use Magento\Sales\Model\Order\Invoice\Total\AbstractTotal;

class ExtraFee extends AbstractTotal
{

    /**
     * Collect
     *
     * @param Invoice $invoice
     * @return void
     */
    public function collect(
        Invoice $invoice
    ) {
        $order = $invoice->getOrder();
        $invoice->setMdoscExtraFee(0);
        $invoice->setBaseMdoscExtraFee(0);
        $amount = $order->getMdoscExtraFee();
        $invoice->setMdoscExtraFee($amount);
        $amount = $order->getBaseMdoscExtraFee();
        $invoice->setBaseMdoscExtraFee($amount);
        $invoice->setGrandTotal($invoice->getGrandTotal() + $invoice->getMdoscExtraFee());
        $invoice->setBaseGrandTotal($invoice->getBaseGrandTotal() + $invoice->getMdoscExtraFee());
        return $this;
    }
}
