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

class OrderPlaceAfter implements \Magento\Framework\Event\ObserverInterface
{
    /**
     * @var \Magento\Checkout\Model\Session
     */
    protected $_checkoutSession;

    /**
     * Construct
     *
     * @param \Magento\Checkout\Model\Session $checkoutSession
     */
    public function __construct(
        \Magento\Checkout\Model\Session $checkoutSession
    ) {
        $this->_checkoutSession = $checkoutSession;
    }

    /**
     * Execute
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return void
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $order = $observer->getEvent()->getOrder();
        $comment = $this->_checkoutSession->getData('onestepcheckout_order_comments', true);
        $deliveryDate = $this->_checkoutSession->getData('md_osc_delivery_date', true);
        $deliveryTime = $this->_checkoutSession->getData('md_osc_delivery_time', true);
        $deliveryComment = $this->_checkoutSession->getData('md_osc_delivery_comment', true);

        if ($comment) {
            $order->addStatusHistoryComment($comment);
        }
        if ($deliveryDate) {
            $order->setData('md_osc_delivery_date', $deliveryDate);
        }
        if ($deliveryTime) {
            $order->setData('md_osc_delivery_time', $deliveryTime);
        }
        if ($deliveryComment) {
            $order->setData('md_osc_delivery_comment', $deliveryComment);
        }
    }
}
