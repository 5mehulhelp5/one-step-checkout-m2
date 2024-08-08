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

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Framework\Controller\ResultFactory;

class RedirectCheckout implements ObserverInterface
{
    /**
     * Construct
     *
     * @param CheckoutSession $checkoutSession
     * @param ResultFactory $resultFactory
     * @param \Magento\Framework\UrlInterface $url
     * @param \Mavenbird\OneStepCheckout\Helper\Data $data
     */
    public function __construct(
        CheckoutSession $checkoutSession,
        ResultFactory $resultFactory,
        \Magento\Framework\UrlInterface $url,
        \Mavenbird\OneStepCheckout\Helper\Data $data
    ) {
        $this->checkoutSession = $checkoutSession;
        $this->resultFactory = $resultFactory;
        $this->url = $url;
        $this->helper = $data;
    }

    /**
     * Execute
     *
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer)
    {
        if ($this->helper->allowRedirectCheckoutAfterProductAddToCart()) {
            if (!$observer->getEvent()->getRequest()->isAjax()) {
                $redirectUrl = $this->url->getUrl('checkout', ['_secure' => true]);
                $observer->getEvent()->getResponse()->setRedirect($redirectUrl);
            }
            $this->checkoutSession->setNoCartRedirect(true);
        }
    }
}
