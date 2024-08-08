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

namespace Mavenbird\OneStepCheckout\Observer\Payment\Model\Cart;

use Magento\Framework\Event\Observer as EventObserver;
use Magento\Framework\Event\ObserverInterface;
use Magento\Quote\Model\QuoteRepository;

class CollectTotalsAndAmounts implements ObserverInterface
{
    /**
     * QuoteRepositorys
     *
     * @var [type]
     */
    protected $quoteRepository;

    /**
     * Construct
     *
     * @param QuoteRepository $quoteRepository
     */
    public function __construct(
        QuoteRepository $quoteRepository
    ) {
        $this->quoteRepository = $quoteRepository;
    }

    /**
     * Execute
     *
     * @param EventObserver $observer
     * @return void
     */
    public function execute(EventObserver $observer)
    {
        /** @var \Magento\Paypal\Model\Cart $cart */
        $cart = $observer->getCart();
        $id = $cart->getSalesModel()->getDataUsingMethod('entity_id');
        if (!$id) {
            $id = $cart->getSalesModel()->getDataUsingMethod('quote_id');
        }
        $quote = $this->quoteRepository->get($id);

        $labels = [];
        $baseFeeAmount = 0;

        if ($quote->getBaseMdoscExtraFee()) {
            $baseFeeAmount = $quote->getBaseMdoscExtraFee();
        }

        $cart->addCustomItem(
            implode(', ', $labels),
            1,
            $baseFeeAmount
        );
    }
}
