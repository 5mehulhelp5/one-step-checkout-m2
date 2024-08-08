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

use Mavenbird\OneStepCheckout\Api\RegistrationInterface;
use Mavenbird\OneStepCheckout\Helper\Data as OscHelper;
use Magento\Customer\Model\Customer;
use Magento\Customer\Model\Session;
use Magento\Framework\Indexer\IndexerRegistry;
use Magento\Framework\Stdlib\Cookie\CookieMetadataFactory;
use Magento\Framework\Stdlib\CookieManagerInterface;
use Psr\Log\LoggerInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

/**
 * Class Submit
 */
class CreateAccount implements ObserverInterface
{
    /**
     * @var RegistrationInterface
     */
    private $registerInterface;

    /**
     * @var Session
     */
    private $customerSession;

    /**
     * @var IndexerRegistry
     */
    private $indexerRegistry;

    /**
     * @var CookieManagerInterface
     */
    private $cookieManager;

    /**
     * @var CookieMetadataFactory
     */
    private $cookieMetadataFactory;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var OscHelper
     */
    private $helper;

    /**
     * Construct
     *
     * @param RegistrationInterface $registerInterface
     * @param Session $customerSession
     * @param IndexerRegistry $indexerRegistry
     * @param CookieManagerInterface $cookieManager
     * @param CookieMetadataFactory $cookieMetadataFactory
     * @param LoggerInterface $logger
     * @param OscHelper $helper
     */
    public function __construct(
        RegistrationInterface $registerInterface,
        Session $customerSession,
        IndexerRegistry $indexerRegistry,
        CookieManagerInterface $cookieManager,
        CookieMetadataFactory $cookieMetadataFactory,
        LoggerInterface $logger,
        OscHelper $helper
    ) {
        $this->registerInterface = $registerInterface;
        $this->customerSession = $customerSession;
        $this->indexerRegistry = $indexerRegistry;
        $this->cookieManager = $cookieManager;
        $this->cookieMetadataFactory = $cookieMetadataFactory;
        $this->logger = $logger;
        $this->helper = $helper;
    }

    /**
     * @inheritdoc
     */
    public function execute(Observer $observer)
    {
        if ($observer->getQuote()->getData('mdosc_registration_token')
        ) {
            if ($observer->getException()) {
                $this->registerInterface->deleteHashToken($observer->getOrder());
            } else {
                try {
                    /** @var \Magento\Customer\Model\Data\Customer|bool $account */
                    $account = $this->registerInterface->createUser($observer->getOrder());

                    if ($account) {
                        $this->indexerRegistry->get(Customer::CUSTOMER_GRID_INDEXER_ID)->reindexRow($account->getId());
                        $this->login($account->getId());
                    }
                } catch (\Exception $exception) {
                    $this->logger->critical($exception->getMessage());
                }
            }
        }
    }

    /**
     * Login
     *
     * @param [type] $accountId
     * @return void
     */
    private function login($accountId)
    {
        $this->customerSession->loginById($accountId);

        if ($this->cookieManager->getCookie('mage-cache-sessid')) {
            $metadata = $this->cookieMetadataFactory->createCookieMetadata();
            $metadata->setPath('/');
            $this->cookieManager->deleteCookie('mage-cache-sessid', $metadata);
        }
    }
}
