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

class OrderSaveAfter implements \Magento\Framework\Event\ObserverInterface
{

    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;

    /**
     * @var \Magento\Checkout\Model\Session
     */
    private $checkoutSession;
    
    /**
     * @var \Magento\Newsletter\Model\SubscriberFactory
     */
    private $subscriberFactory;

    /**
     * @var \Mavenbird\OneStepCheckout\Model\DeliveryDate
     */
    protected $deliveryModel;

    /**
     * @var \Mavenbird\OneStepCheckout\Helper\Data
     */
    protected $oscHelper;

    /**
     * Construct
     *
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Magento\Checkout\Model\Session $checkoutSession
     * @param \Magento\Newsletter\Model\SubscriberFactory $subscriberFactory
     * @param \Mavenbird\OneStepCheckout\Helper\Data $oscHelper
     * @param array $data
     */
    public function __construct(
        \Psr\Log\LoggerInterface $logger,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Newsletter\Model\SubscriberFactory $subscriberFactory,
        \Mavenbird\OneStepCheckout\Helper\Data $oscHelper,
        array $data = []
    ) {
        $this->logger = $logger;
        $this->checkoutSession = $checkoutSession;
        $this->subscriberFactory = $subscriberFactory;
        $this->oscHelper = $oscHelper;
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
        $newsletter = $this->checkoutSession->getData('onestepcheckout_newsletter', true);
        if ($newsletter) {
            $this->saveSubscriber($order);
        }
    }
    
    /**
     * GetSubscriberEmail
     *
     * @param [type] $order
     * @return void
     */
    private function getSubscriberEmail($order)
    {
        if ($order->getShippingAddress()) {
            return $order->getShippingAddress()->getEmail();
        } elseif ($order->getBillingAddress()) {
            return $order->getBillingAddress()->getEmail();
        }
        return false;
    }
    
    /**
     * SaveSubscriber
     *
     * @param [type] $order
     * @return void
     */
    private function saveSubscriber($order)
    {
        if ($email = $this->getSubscriberEmail($order)) {
            $subscriberModel = $this->subscriberFactory->create()->loadByEmail($email);
            if (!$subscriberModel->getId()) {
                try {
                    $this->subscriberFactory->create()->subscribe($email);
                } catch (\Magento\Framework\Exception\LocalizedException $e) {
                    $this->logger->notice($e->getMessage());
                } catch (\Exception $e) {
                    $this->logger->notice($e->getMessage());
                }

            } elseif ($subscriberModel->getData('subscriber_status') != 1) {
                $subscriberModel->setData('subscriber_status', 1);
                try {
                    $subscriberModel->save();
                } catch (\Exception $e) {
                    $this->logger->notice($e->getMessage());
                }
            }
        }
    }
}
