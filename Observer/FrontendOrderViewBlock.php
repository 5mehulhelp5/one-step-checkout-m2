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
use Magento\Framework\View\Element\TemplateFactory;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;

class FrontendOrderViewBlock implements ObserverInterface
{
    /**
     * @var TemplateFactory
     */
    protected $templateFactory;

    /**
     * @var TimezoneInterface
     */
    private $timezone;

    /**
     * Construct
     *
     * @param TemplateFactory $templateFactory
     * @param TimezoneInterface $timezone
     */
    public function __construct(
        TemplateFactory $templateFactory,
        TimezoneInterface $timezone
    ) {
        $this->templateFactory = $templateFactory;
        $this->timezone = $timezone;
    }

    /**
     * Execute
     *
     * @param EventObserver $observer
     * @return void
     */
    public function execute(EventObserver $observer)
    {
        $element = $observer->getElementName();
        if ($element == 'sales.order.info') {
            $orderViewBlock = $observer->getLayout()->getBlock($element);
            $order = $orderViewBlock->getOrder();

            if ($order->getMdOscDeliveryDate() != '0000-00-00') {
                // $formattedDate = $this->timezone->formatDate(
                //     $order->getMdOscDeliveryDate(),
                //     \IntlDateFormatter::MEDIUM
                // );
                $formattedDate = date("d M Y", strtotime($order->getMdOscDeliveryDate()));
            } else {
                $formattedDate = '';
            }

            /** @var \Magento\Framework\View\Element\Template $deliveryDateBlock */
            $deliveryDateBlock = $this->templateFactory->create();
            $deliveryDateBlock->setMdOscDeliveryDate($formattedDate);
            $deliveryDateBlock->setMdOscDeliveryTime($order->getMdOscDeliveryTime());
            $deliveryDateBlock->setMdOscDeliveryComment($order->getMdOscDeliveryComment());
            $deliveryDateBlock->setTemplate('Mavenbird_OneStepCheckout::delivery_date_shipping_info.phtml');
            $html = $observer->getTransport()->getOutput() . $deliveryDateBlock->toHtml();
            $observer->getTransport()->setOutput($html);
        }
        return $this;
    }
}
