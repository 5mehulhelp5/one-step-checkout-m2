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

namespace Mavenbird\OneStepCheckout\Observer;

use Magento\Framework\Event\Observer as EventObserver;
use Magento\Framework\Event\ObserverInterface;
use Magento\Quote\Model\QuoteRepository;
use Mavenbird\OneStepCheckout\Helper\Data;

class SalesModelServiceQuoteSubmitBefore implements ObserverInterface
{
    /**
     * @var QuoteRepository
     */
    private $quoteRepository;

    /**
     * @var Data
     */
    private $oscHelper;

    /**
     * Construct
     *
     * @param QuoteRepository $quoteRepository
     * @param Data $oscHelper
     */
    public function __construct(
        QuoteRepository $quoteRepository,
        Data $oscHelper
    ) {
        $this->quoteRepository = $quoteRepository;
        $this->oscHelper = $oscHelper;
    }

    /**
     * Execute
     *
     * @param EventObserver $observer
     * @return void
     */
    public function execute(EventObserver $observer)
    {
        $order = $observer->getOrder();
        /** @var \Magento\Quote\Model\Quote $quote */
        $quote = $this->quoteRepository->get($order->getQuoteId());
        if ($quote->getMdoscExtraFee()) {
            $extraFee = $quote->getMdoscExtraFee();
            $extraBaseFee = $quote->getBaseMdoscExtraFee();
            $order->setData('mdosc_extra_fee', $extraFee);
            $order->setData('base_mdosc_extra_fee', $extraBaseFee);
        }

        return $this;
    }
}
