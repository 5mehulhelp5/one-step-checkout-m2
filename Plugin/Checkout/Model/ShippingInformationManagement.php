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

namespace Mavenbird\OneStepCheckout\Plugin\Checkout\Model;

use Magento\Quote\Model\QuoteRepository;
use Magento\Checkout\Api\Data\ShippingInformationInterface;
use Mavenbird\OneStepCheckout\Helper\Data as OscHelper;
use Magento\Framework\Encryption\EncryptorInterface;

class ShippingInformationManagement
{
    /**
     * @var QuoteRepository
     */
    protected $quoteRepository;

    /**
     * @var OscHelper
     */
    protected $helper;

    /**
     * @var EncryptorInterface
     */
    protected $encrypt;

    /**
     * Construct
     *
     * @param QuoteRepository $quoteRepository
     * @param OscHelper $helper
     * @param EncryptorInterface $encrypt
     */
    public function __construct(
        QuoteRepository $quoteRepository,
        OscHelper $helper,
        EncryptorInterface $encrypt
    ) {
        $this->quoteRepository = $quoteRepository;
        $this->helper = $helper;
        $this->encrypt = $encrypt;
    }

    /**
     * BeforeSaveAddressInformation
     *
     * @param \Magento\Checkout\Model\ShippingInformationManagement $subject
     * @param [type] $cartId
     * @param ShippingInformationInterface $addressInformation
     * @return void
     */
    public function beforeSaveAddressInformation(
        \Magento\Checkout\Model\ShippingInformationManagement $subject,
        $cartId,
        ShippingInformationInterface $addressInformation
    ) {
        $quote = $this->quoteRepository->getActive($cartId);
        $extAttributes = $addressInformation->getExtensionAttributes();
        if ($extAttributes->getMdoscExtraFeeChecked()) {
            $quote->setData('mdosc_extra_fee_checked', $extAttributes->getMdoscExtraFeeChecked());
        }
    }
}
