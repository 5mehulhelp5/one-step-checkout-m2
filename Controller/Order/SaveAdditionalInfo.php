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

namespace Mavenbird\OneStepCheckout\Controller\Order;

class SaveAdditionalInfo extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */
    protected $resultJsonFactory;

    /**
     * @var \Magento\Framework\Json\Helper\Data
     */
    protected $jsonHelper;

    /**
     * @var \Magento\Framework\DataObjectFactory
     */
    protected $dataObjectFactory;
    /**
     * @var \Magento\Checkout\Model\Session
     */
    protected $checkoutSession;

    /**
     * @var \Mavenbird\OneStepCheckout\Helper\Data
     */
    protected $oscHelper;

    /**
     * Construct
     *
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Framework\Json\Helper\Data $jsonHelper
     * @param \Magento\Framework\DataObjectFactory $dataObjectFactory
     * @param \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
     * @param \Magento\Checkout\Model\Session $checkoutSession
     * @param \Mavenbird\OneStepCheckout\Helper\Data $oscHelper
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\Json\Helper\Data $jsonHelper,
        \Magento\Framework\DataObjectFactory $dataObjectFactory,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Mavenbird\OneStepCheckout\Helper\Data $oscHelper
    ) {
        parent::__construct($context);
        $this->resultJsonFactory = $resultJsonFactory;
        $this->jsonHelper = $jsonHelper;
        $this->dataObjectFactory = $dataObjectFactory;
        $this->checkoutSession = $checkoutSession;
        $this->oscHelper = $oscHelper;
    }

    /**
     * Execute
     *
     * @return void
     */
    public function execute()
    {
        $additionalData = $this->dataObjectFactory->create([
            'data' => $this->jsonHelper->jsonDecode($this->getRequest()->getContent()),
        ]);
        $quote = $this->checkoutSession->getQuote();

        if (!$quote->isVirtual()) {
            if ($quote->getShippingAddress()->getShippingMethod() != 'storepickup_storepickup') {
                if ($this->oscHelper->isDeliveryDateEnabled()) {
                    if ($this->oscHelper->isDeliveryDateRequired() && $additionalData->getData('md_osc_delivery_date') == '0000-00-00') {
                        throw new \Exception(__('Invalid Delivery Date'));
                    }
                }
            }
        }
        $this->checkoutSession->setData(
            'onestepcheckout_newsletter',
            $additionalData->getData('onestepcheckout_newsletter')
        );
        $this->checkoutSession->setData(
            'onestepcheckout_order_comments',
            $additionalData->getData('onestepcheckout_order_comments')
        );
        $this->checkoutSession->setData(
            'md_osc_delivery_date',
            $additionalData->getData('md_osc_delivery_date')
        );
        $this->checkoutSession->setData(
            'md_osc_delivery_time',
            $additionalData->getData('md_osc_delivery_time')
        );
        $this->checkoutSession->setData(
            'md_osc_delivery_comment',
            $additionalData->getData('md_osc_delivery_comment')
        );
    }
}
