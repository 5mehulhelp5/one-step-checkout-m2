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
use Magento\Framework\App\Config\ScopeConfigInterface;
use Mavenbird\OneStepCheckout\Helper\Data;

class SystemConfigSave implements ObserverInterface
{
    /**
     * @var \Magento\Config\Model\ResourceModel\Config
     */
    protected $resourceConfig;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var \Mavenbird\OneStepCheckout\Helper\Data
     */
    protected $helper;

    /**
     * Construct
     *
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Config\Model\ResourceModel\Config $resourceConfig
     * @param \Mavenbird\OneStepCheckout\Helper\Data $helper
     */
    public function __construct(
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Config\Model\ResourceModel\Config $resourceConfig,
        \Mavenbird\OneStepCheckout\Helper\Data $helper
    ) {
        $this->storeManager = $storeManager;
        $this->resourceConfig = $resourceConfig;
        $this->helper = $helper;
    }

    /**
     * Execute
     *
     * @param EventObserver $observer
     * @return void
     */
    public function execute(EventObserver $observer)
    {
        $scopeId = 1;

        $this->resourceConfig->saveConfig(
            Data::XPATH_CONFIG_GIFT_MESSAGE_ALLOW_ORDER,
            $this->helper->getGiftMessageOrderLevel(),
            ScopeConfigInterface::SCOPE_TYPE_DEFAULT,
            $scopeId
        );

        $this->resourceConfig->saveConfig(
            Data::XPATH_CONFIG_GIFT_MESSAGE_ALLOW_ITEMS,
            $this->helper->getGiftMessageItemLevel(),
            ScopeConfigInterface::SCOPE_TYPE_DEFAULT,
            $scopeId
        );

        $this->resourceConfig->saveConfig(
            Data::XML_PATH_CHECKOUT_BILLING_ADDRESS,
            $this->helper->getBillingAddressBlock() == 'shipping' ? '1' : '0',
            ScopeConfigInterface::SCOPE_TYPE_DEFAULT,
            $scopeId
        );
        if ($this->helper->getAddressLine()) {
            $this->resourceConfig->saveConfig(
                Data::XML_PATH_CUSTOMER_ADDRESS_LINE,
                $this->helper->getAddressLine(),
                ScopeConfigInterface::SCOPE_TYPE_DEFAULT,
                $scopeId
            );
        }
    }
}
