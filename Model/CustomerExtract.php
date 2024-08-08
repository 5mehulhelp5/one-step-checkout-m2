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

use Magento\Framework\DataObject\Copy;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Customer\Model\Data\CustomerFactory;
use Magento\Customer\Model\Data\AddressFactory;
use Magento\Customer\Model\Data\RegionFactory;
use Magento\Quote\Model\Quote\AddressFactory as QuoteAddress;
use Magento\Sales\Model\Order\Address as OrderAddress;
use Magento\Sales\Api\Data\OrderInterface;

class CustomerExtract
{
    /**
     * @var AddressFactory
     */
    private $addressFactory;

    /**
     * @var RegionFactory
     */
    private $regionFactory;

    /**
     * @var CustomerFactory
     */
    private $customerFactory;

    /**
     * @var OrderRepositoryInterface
     */
    private $orderRepository;

    /**
     * @var CustomerRepositoryInterface
     */
    private $customerRepository;

    /**
     * @var Copy
     */
    private $objectCopy;

    /**
     * @var QuoteAddress
     */
    private $quoteAddress;

    /**
     * Construct
     *
     * @param AddressFactory $addressFactory
     * @param RegionFactory $regionFactory
     * @param CustomerFactory $customerFactory
     * @param OrderRepositoryInterface $orderRepository
     * @param CustomerRepositoryInterface $customerRepository
     * @param Copy $objectCopy
     * @param QuoteAddress $quoteAddress
     */
    public function __construct(
        AddressFactory $addressFactory,
        RegionFactory $regionFactory,
        CustomerFactory $customerFactory,
        OrderRepositoryInterface $orderRepository,
        CustomerRepositoryInterface $customerRepository,
        Copy $objectCopy,
        QuoteAddress $quoteAddress
    ) {
        $this->addressFactory = $addressFactory;
        $this->regionFactory = $regionFactory;
        $this->customerFactory = $customerFactory;
        $this->orderRepository = $orderRepository;
        $this->customerRepository = $customerRepository;
        $this->objectCopy = $objectCopy;
        $this->quoteAddress = $quoteAddress;
    }

    /**
     * Extract
     *
     * @param OrderInterface $order
     * @return void
     */
    public function extract(OrderInterface $order)
    {
        if ($order->getCustomerId()) {
            return $this->customerRepository->getById($order->getCustomerId());
        }
        $customerData = $this->prepareCustomerData($order);
        $processedAddressData = [];
        $customerAddresses = [];

        /** @var \Magento\Sales\Model\Order\Address $orderAddress */
        foreach ($order->getAddresses() as $orderAddress) {
            $addressData = $this->prepareAddressData($orderAddress);

            $index = array_search($addressData, $processedAddressData);

            if ($index === false) {
                $customerAddress = $this->getCustomerAddress($addressData, $orderAddress);
                $processedAddressData[] = $addressData;
                $customerAddresses[] = $customerAddress;
                $index = count($processedAddressData) - 1;
            }
            $customerAddress = $customerAddresses[$index];
            if ($orderAddress->getAddressType() == OrderAddress::TYPE_BILLING) {
                $customerAddress->setIsDefaultBilling(true);
            }
            if ($orderAddress->getAddressType() == OrderAddress::TYPE_SHIPPING) {
                $customerAddress->setIsDefaultShipping(true);
            }
        }
        $customerData['addresses'] = $customerAddresses;
        return $this->customerFactory->create(['data' => $customerData]);
    }

    /**
     * PrepareCustomerData
     *
     * @param [type] $order
     * @return void
     */
    private function prepareCustomerData($order)
    {
        return $this->objectCopy->copyFieldsetToTarget(
            'order_address',
            'to_customer',
            $order->getBillingAddress(),
            []
        );
    }

    /**
     * PrepareAddressData
     *
     * @param [type] $orderAddress
     * @return void
     */
    private function prepareAddressData($orderAddress)
    {
        return $this->objectCopy->copyFieldsetToTarget(
            'order_address',
            'to_customer_address',
            $orderAddress,
            []
        );
    }

    /**
     * GetCustomerAddress
     *
     * @param [type] $addressData
     * @param [type] $orderAddress
     * @return void
     */
    private function getCustomerAddress($addressData, $orderAddress)
    {
        /** @var \Magento\Customer\Model\Data\Address $customerAddress */
        $customerAddress = $this->addressFactory->create(['data' => $addressData]);
        $customerAddress->setIsDefaultBilling(false);
        $customerAddress->setIsDefaultBilling(false);

        if (is_string($orderAddress->getRegion())) {
            /** @var \Magento\Customer\Model\Data\Region $region */
            $region = $this->regionFactory->create();
            $region->setRegion($orderAddress->getRegion());
            $region->setRegionCode($orderAddress->getRegionCode());
            $region->setRegionId($orderAddress->getRegionId());
            $customerAddress->setRegion($region);
        }

        return $customerAddress;
    }
}
