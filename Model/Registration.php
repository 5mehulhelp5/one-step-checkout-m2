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

namespace Mavenbird\OneStepCheckout\Model;

use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Event\ManagerInterface as EventManagerInterface;
use Mavenbird\OneStepCheckout\Api\RegistrationInterface;
use Magento\Quote\Model\QuoteIdMaskFactory;
use Magento\Quote\Model\QuoteIdMask;
use Magento\Quote\Model\QuoteFactory;
use Psr\Log\LoggerInterface;
use Magento\Framework\Encryption\EncryptorInterface;
use Magento\Customer\Model\Data\Customer;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Customer\Api\AccountManagementInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
use Mavenbird\OneStepCheckout\Helper\Data as OscHelper;

class Registration implements RegistrationInterface
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var QuoteIdMaskFactory
     */
    private $quoteIdMask;

    /**
     * @var EncryptorInterface
     */
    private $encrypt;

    /**
     * @var QuoteFactory
     */
    private $quoteFactory;

    /**
     * @var CustomerExtract
     */
    private $customerExtract;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var AccountManagementInterface
     */
    private $accountManagement;

    /**
     * @var EventManagerInterface
     */
    private $eventManager;

    /**
     * @var OrderRepositoryInterface
     */
    private $orderRepository;

    /**
     * @var OscHelper
     */
    private $helper;

    /**
     * Construct
     *
     * @param QuoteIdMaskFactory $quoteIdMask
     * @param LoggerInterface $logger
     * @param QuoteFactory $quoteFactory
     * @param EncryptorInterface $encrypt
     * @param CustomerExtract $customerExtract
     * @param StoreManagerInterface $storeManager
     * @param AccountManagementInterface $accountManagement
     * @param EventManagerInterface $eventManager
     * @param OrderRepositoryInterface $orderRepository
     * @param OscHelper $helper
     */
    public function __construct(
        QuoteIdMaskFactory $quoteIdMask,
        LoggerInterface $logger,
        QuoteFactory $quoteFactory,
        EncryptorInterface $encrypt,
        CustomerExtract $customerExtract,
        StoreManagerInterface $storeManager,
        AccountManagementInterface $accountManagement,
        EventManagerInterface $eventManager,
        OrderRepositoryInterface $orderRepository,
        OscHelper $helper
    ) {
        $this->quoteIdMask = $quoteIdMask;
        $this->logger = $logger;
        $this->quoteFactory = $quoteFactory;
        $this->encrypt = $encrypt;
        $this->customerExtract = $customerExtract;
        $this->storeManager = $storeManager;
        $this->accountManagement = $accountManagement;
        $this->eventManager = $eventManager;
        $this->orderRepository = $orderRepository;
        $this->helper = $helper;
    }

    /**
     * CreateUser
     *
     * @param [type] $order
     * @return void
     */
    public function createUser($order)
    {
//        if($this->helper->isRegistrationEnabled()) {
            /** @var Customer $customerData */
            $customerData = $this->customerExtract->extract($order);
            $this->setCustomerInformation($customerData, $order);
        if (!$customerData->getId()
                && $this->accountManagement->isEmailAvailable($customerData->getEmail())
            ) {
            $passwordQuote = $this->getPasswordQuote($order->getQuoteId());
            /** @var Customer $account */
            $account = $this->accountManagement->createAccountWithPasswordHash(
                $customerData,
                $passwordQuote
            );

            $this->eventManager->dispatch(
                'customer_register_success',
                ['customer' => $account, 'md_osc_onestepcheckout_register' => true]
            );

            $order->setCustomerId($account->getId());
            $order->setCustomerIsGuest(0);
            $this->orderRepository->save($order);
            $this->deleteHashToken($order);

            return $account;
        }
//        }
    }

    /**
     * GeneratePassword
     *
     * @return void
     */
    private function generatePassword()
    {
        $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890@$';
        $pass = [];
        $alphaLength = strlen($alphabet) - 1;
        $length = 8;
        for ($i = 0; $i < $length; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass);
    }

    /**
     * SaveHashToken
     *
     * @param [type] $cartId
     * @param [type] $token
     * @return void
     */
    public function saveHashToken($cartId, $token)
    {
        if (!$token && $this->helper->isAutoRegistrationEnabled()) {
            $token = $this->generatePassword();
        }
        if ($token) {
            try {
                /** @var QuoteIdMask $quoteIdMask */
                $quoteIdMask = $this->quoteIdMask->create()->load($cartId, 'masked_id');
                $quote =  $this->quoteFactory->create()->load($quoteIdMask->getQuoteId());
                $hasToken = $this->createHashToken($token);
                $quote->setData('mdosc_registration_token', $hasToken)->save();
            } catch (\Exception $exception) {
                $this->logger->critical($exception->getMessage());
            }
        }
        return true;
    }

    /**
     * DeleteHashToken
     *
     * @param [type] $order
     * @return void
     */
    public function deleteHashToken($order)
    {
        if ($order) {
            try {
                $quote = $this->quoteFactory->create()->load($order->getQuoteId());
                $quote->setData('mdosc_registration_token', '')->save();
            } catch (\Exception $exception) {
                return true;
            }
        }

        return true;
    }

    /**
     * CreateHashToken
     *
     * @param [type] $token
     * @return void
     */
    private function createHashToken($token)
    {
        return $this->encrypt->getHash($token, true);
    }

    /**
     * SetCustomerInformation
     *
     * @param Customer $customerData
     * @param OrderInterface $order
     * @return void
     */
    private function setCustomerInformation(Customer $customerData, OrderInterface $order)
    {
        if (!$customerData->getStoreId()) {
            if ($customerData->getWebsiteId()) {
                $storeId = $this->storeManager->getWebsite($customerData->getWebsiteId())->getDefaultStore()->getId();
            } else {
                $this->storeManager->setCurrentStore(null);
                $storeId = $this->storeManager->getStore()->getId();
            }
            $customerData->setStoreId($storeId);
        }
        if (!$customerData->getWebsiteId()) {
            $websiteId = $this->storeManager->getStore($customerData->getStoreId())->getWebsiteId();
            $customerData->setWebsiteId($websiteId);
        }
        if (!$customerData->getTaxvat()) {
            $customerData->setTaxvat($order->getShippingAddress()->getVatId());
        }
    }

    /**
     * GetPasswordQuote
     *
     * @param [type] $quoteId
     * @return void
     */
    private function getPasswordQuote($quoteId)
    {
        try {
            $quote =  $this->quoteFactory->create()->load($quoteId);
            $quoteToken = $quote->getData('mdosc_registration_token');
        } catch (NoSuchEntityException $exception) {
            $this->logger->critical($exception->getMessage());
        }
        return $quoteToken;
    }
}
