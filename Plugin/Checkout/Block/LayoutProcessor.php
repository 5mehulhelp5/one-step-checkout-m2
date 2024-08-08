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

namespace Mavenbird\OneStepCheckout\Plugin\Checkout\Block;

use Magento\Framework\Stdlib\ArrayManager;
use Mavenbird\OneStepCheckout\Helper\Data;
use Mavenbird\OneStepCheckout\Model\Address\Form\DefaultSortOrder;
use Mavenbird\Core\Helper\Data as AmazonHelper;
use Magento\Checkout\Model\Session;
use Mavenbird\OneStepCheckout\Model\JsLayoutAccessData as AccessData;
use Mavenbird\OneStepCheckout\Model\JsLayoutAccessDataFactory as AccessDataFactory;

class LayoutProcessor
{
    public const TEMPLATE_PATH = 'components.checkout.config.template';
    public const DEFAULT_DISCOUNT_BLOCK = 'components.checkout.children.steps.children.billing-step.children.payment.children.afterMethods.children.discount';
    public const CART_ITEMS_COMPONENT = 'components.checkout.children.sidebar.children.summary.children.cart_items';
    public const CART_ITEM_THUMBNAIL_COMPONENT = self::CART_ITEMS_COMPONENT.'.children.details.children.thumbnail.component';
    public const BEFORE_OSC_BUTTON = 'components.checkout.children.before-osc-button';
    public const COMMENTS_BLOCK = self::BEFORE_OSC_BUTTON.'.children.comments';
    public const NEWSLETTER_BLOCK = self::BEFORE_OSC_BUTTON.'.children.newsletter';
    public const DISCOUNT_BLOCK = self::BEFORE_OSC_BUTTON.'.children.discount';
    public const SHIPPING_STEP = 'components.checkout.children.steps.children.shipping-step';
    public const BILLING_STEP = 'components.checkout.children.steps.children.billing-step';
    public const DEFAULT_BEFORE_PLACE_ORDER_BLOCK = self::BILLING_STEP.'.children.payment.children.payments-list.children.before-place-order.children';
    public const DEFAULT_AGREEMENT_BLOCK = self::BILLING_STEP.'.children.payment.children.payments-list.children.before-place-order.children.agreements';
    public const SIDEBAR_AGREEMENT_BLOCK = self::BEFORE_OSC_BUTTON.'.children.agreements';
    public const AGREEMENT_BLOCK_VALIDATOR = self::BILLING_STEP.'.children.payment.children.additional-payment-validators.children.agreements-validator.component';
    public const SHIPPING_METHOD = 'components.checkout.children.steps.children.shippingMethods';
    public const DELIVERY_DATE_BLOCK = self::SHIPPING_METHOD.'.children.mdosc-delivery-date';
    public const EXTRA_FEE_BLOCK = self::BEFORE_OSC_BUTTON.'.children.extraFee';
    public const ORDER_LEVEL_GIFT_MESSAGE = self::BEFORE_OSC_BUTTON.'.children.giftMessage';
    public const ITEM_LEVEL_GIFT_MESSAGE = self::CART_ITEMS_COMPONENT.'.children.details.children';
    public const DEFAULT_PLACE_ORDER_BUTTON = self::BEFORE_OSC_BUTTON.'.children.place-order-button';
    public const PAYMENT_PLACE_ORDER_BUTTON = self::BILLING_STEP.'.children.payment.children.payments-list.children.before-place-order.children.place-order-button';
    public const CUSTOMER_EMAIL_BLOCK = self::SHIPPING_STEP.'.children.shippingAddress.children.customer-email';

    /**
     * @var \Mavenbird\OneStepCheckout\Helper\Data
     */
    private $oscHelper;

    /**
     * @var AmazonHelper
     */
    private $amazonHelper;

    /**
     * @var Session
     */
    private $checkoutSession;

    /**
     * Construct
     *
     * @param Data $oscHelper
     * @param ArrayManager $arrayManager
     * @param DefaultSortOrder $defaultSortOrder
     * @param AmazonHelper $amazonHelper
     * @param Session $checkoutSession
     * @param AccessDataFactory $accessData
     */
    public function __construct(
        Data $oscHelper,
        ArrayManager $arrayManager,
        DefaultSortOrder $defaultSortOrder,
        AmazonHelper $amazonHelper,
        Session $checkoutSession,
        AccessDataFactory $accessData
    ) {
        $this->oscHelper = $oscHelper;
        $this->arrayManager = $arrayManager;
        $this->defaultSortOrder = $defaultSortOrder;
        $this->amazonHelper = $amazonHelper;
        $this->checkoutSession = $checkoutSession;
        $this->accessData = $accessData;
    }

    /**
     * AfterProcess
     *
     * @param \Magento\Checkout\Block\Checkout\LayoutProcessor $subject
     * @param [type] $jsLayout
     * @return void
     */
    public function afterProcess(\Magento\Checkout\Block\Checkout\LayoutProcessor $subject, $jsLayout)
    {
        if (!$this->oscHelper->isModuleEnable()) {
            return $jsLayout;
        }
        $quote = $this->checkoutSession->getQuote();
        
        $data = $this->accessData->create(['data' => $jsLayout]);
        if ($this->oscHelper->getLayout() == '2column') {
            $this->changeLayout($data);
        }
        $this->setCartItemBlock($data);
        $this->removeBlock($data, $quote);
        if ($this->oscHelper->showAgreements() == 'sidebar') {
            $this->changeAgreementBlock($data);
        }
        if ($this->oscHelper->isDeliveryDateEnabled()) {
            $data->setArray(self::DELIVERY_DATE_BLOCK, $this->getDeliveryLayout());
        }
        if ($this->oscHelper->isGiftMessageItemLevel()) {
            $this->setItemLevelGiftMessage($data, $quote);
        }
        if (!empty($this->oscHelper->getStepConfig())) {
            $steps = $this->oscHelper->getStepConfig();
            $this->setStepSortOrder($data, $steps);
        }
        if ($this->oscHelper->getPlaceOrderPosition() == 'payment') {
            $data->removeArray(self::DEFAULT_PLACE_ORDER_BUTTON);
        } else {
            $data->removeArray(self::PAYMENT_PLACE_ORDER_BUTTON);
        }
        if ($this->oscHelper->isRegistrationEnabled()) {
            $this->setRegistrationBlock($data);
        }
        if ($this->oscHelper->getBillingAddressBlock() == 'shipping') {
            $this->changeBillingAddressBlock($data);
        }
//        $this->FixedAmazonePay($data, $quote);
//        $this->FixedStorePickup($data);

        $jsLayout = $data->exportArray();
        return $jsLayout;
    }

    /**
     * SetCartItemBlock
     *
     * @param AccessData $data
     * @return void
     */
    private function setCartItemBlock(AccessData $data)
    {
        if ($data->hasArray(self::CART_ITEMS_COMPONENT)) {
            $data->setArray(
                self::CART_ITEMS_COMPONENT.'.component',
                "Mavenbird_OneStepCheckout/js/view/summary/cart-items"
            );
            $data->setArray(
                self::CART_ITEMS_COMPONENT.'.displayArea',
                "item-review"
            );
        }
        if ($data->hasArray(self::CART_ITEMS_COMPONENT.'.children.details.component')) {
            $data->setArray(
                self::CART_ITEMS_COMPONENT.'.children.details.component',
                'Mavenbird_OneStepCheckout/js/view/summary/item/details'
            );
        }
        if ($data->hasArray(self::CART_ITEM_THUMBNAIL_COMPONENT)) {
            $data->setArray(
                self::CART_ITEM_THUMBNAIL_COMPONENT,
                'Mavenbird_OneStepCheckout/js/view/summary/item/details/thumbnail'
            );
        }
    }

    /**
     * ChangeLayout
     *
     * @param AccessData $data
     * @return void
     */
    private function changeLayout(AccessData $data)
    {
        $data->setArray(
            self::TEMPLATE_PATH,
            'Mavenbird_OneStepCheckout/onestepcheckout-2column'
        );
    }

    /**
     * RemoveDiscountBlock
     *
     * @param AccessData $data
     * @return void
     */
    private function removeDiscountBlock(AccessData $data)
    {
        if ($data->hasArray(self::DEFAULT_DISCOUNT_BLOCK)) {
            $data->removeArray(self::DEFAULT_DISCOUNT_BLOCK);
        }
    }

    /**
     * RemoveBlock
     *
     * @param AccessData $data
     * @param [type] $quote
     * @return void
     */
    private function removeBlock(AccessData $data, $quote)
    {
        $this->removeDiscountBlock($data);
        if (!$this->oscHelper->showComments()) {
            if ($data->hasArray(self::COMMENTS_BLOCK)) {
                $data->removeArray(self::COMMENTS_BLOCK);
            }
        }
        if (!$this->oscHelper->showNewsletter()) {
            if ($data->hasArray(self::NEWSLETTER_BLOCK)) {
                $data->removeArray(self::NEWSLETTER_BLOCK);
            }
        }
        if (!$this->oscHelper->showDiscountCoupon()) {
            if ($data->hasArray(self::DISCOUNT_BLOCK)) {
                $data->removeArray(self::DISCOUNT_BLOCK);
            }
        }
        if (!$this->oscHelper->showProductThumbnail()) {
            if ($data->hasArray(self::CART_ITEMS_COMPONENT.'.children.details.children.thumbnail')) {
                $data->removeArray(self::CART_ITEMS_COMPONENT.'.children.details.children.thumbnail');
            }
        }
        if (!$this->oscHelper->isGiftMessageOrderLevel()) {
            if ($data->hasArray(self::ORDER_LEVEL_GIFT_MESSAGE)) {
                $data->removeArray(self::ORDER_LEVEL_GIFT_MESSAGE);
            }
        }
        /* @var $quote \Magento\Quote\Model\Quote */
        if ($quote->isVirtual()) {
            if ($data->hasArray(self::EXTRA_FEE_BLOCK)) {
                $data->removeArray(self::EXTRA_FEE_BLOCK);
            }
        }
    }

    /**
     * ChangeAgreementBlock
     *
     * @param AccessData $data
     * @return void
     */
    private function changeAgreementBlock(AccessData $data)
    {

        if ($data->hasArray(self::DEFAULT_AGREEMENT_BLOCK)) {
            $data->setArray(
                self::SIDEBAR_AGREEMENT_BLOCK,
                $data->getArray(self::DEFAULT_AGREEMENT_BLOCK)
            );
            $data->setArray(self::SIDEBAR_AGREEMENT_BLOCK.'.sortOrder', '4');
            if ($data->hasArray(self::AGREEMENT_BLOCK_VALIDATOR)) {
                $data->setArray(self::AGREEMENT_BLOCK_VALIDATOR, 'Mavenbird_OneStepCheckout/js/view/agreement-validation');
            }
            $data->removeArray(self::DEFAULT_AGREEMENT_BLOCK);
        }
    }

    /**
     * SetItemLevelGiftMessage
     *
     * @param AccessData $data
     * @param [type] $quote
     * @return void
     */
    private function setItemLevelGiftMessage(AccessData $data, $quote)
    {
        /* @var $quote \Magento\Quote\Model\Quote */
        //checkout.sidebar.summary.cart_items.details.gift_message_
        $items = $quote->getAllVisibleItems();
        foreach ($items as $item) {
            $id = $item->getItemId();
            $giftMessage = self::ITEM_LEVEL_GIFT_MESSAGE.'.gift_message_'.$id;
            //echo "<pre>"; print_r($giftMessage);
            $data->setArray($giftMessage.'.component', 'Mavenbird_OneStepCheckout/js/view/gift-message/content');
            $data->setArray($giftMessage.'.config.template', 'Mavenbird_OneStepCheckout/gift-message/content');
            $data->setArray($giftMessage.'.config.formTemplate', 'Mavenbird_OneStepCheckout/gift-message/form');
            $data->setArray($giftMessage.'.config.itemId', $id);
            $data->setArray($giftMessage.'.config.itemName', $item->getName());
            $data->setArray($giftMessage.'.displayArea', 'gift_message_'.$id);
        }
    }

    /**
     * SetStepSortOrder
     *
     * @param AccessData $data
     * @param [type] $steps
     * @return void
     */
    private function setStepSortOrder(AccessData $data, $steps)
    {
        $allSteps = [
            'shipping_adddress' => self::SHIPPING_STEP.'.sortOrder',
            'payment' => self::BILLING_STEP.'.sortOrder',
            'shipping_method' => self::SHIPPING_METHOD.'.sortOrder',
            'review' => 'components.checkout.children.steps.children.order-review.sortOrder'

        ];
        foreach ($allSteps as $key => $step) {
            $data->setArray($step, $steps['rows'][$key]['sort_order']);
        }
    }

    /**
     * SetRegistrationBlock
     *
     * @param AccessData $data
     * @return void
     */
    private function setRegistrationBlock(AccessData $data)
    {
        $allSteps = [
            'component' => 'Mavenbird_OneStepCheckout/js/view/form/element/email',
            'requiredPasswordCharacter' => (int) $this->oscHelper->getRequiredPasswordCharacter(),
            'minimumPasswordLength' => (int) $this->oscHelper->getMinimumPasswordLength()
        ];
        foreach ($allSteps as $key => $step) {
            $data->setArray(self::CUSTOMER_EMAIL_BLOCK.'.'.$key, $step);
        }
    }

    /**
     * ChangeBillingAddressBlock
     *
     * @param AccessData $data
     * @return void
     */
    private function changeBillingAddressBlock(AccessData $data)
    {
        $billingStep = self::BILLING_STEP.'.children.payment.children.afterMethods.children.billing-address-form';
        $shippingStep = self::SHIPPING_STEP.'.children.billing-address-form';
        if ($data->hasArray($billingStep)) {
            $data->setArray($shippingStep, $data->getArray($billingStep));
            $data->setArray($shippingStep.'.sortOrder', 2);
            $data->removeArray($billingStep);
        }
    }

    /**
     * FixedAmazonePay
     *
     * @param AccessData $data
     * @param [type] $quote
     * @return void
     */
    private function FixedAmazonePay(AccessData $data, $quote)
    {
        /* @var $quote \Magento\Quote\Model\Quote */
        if (!$quote->isVirtual() && $this->amazonHelper->isPwaEnabled()) {
            $shippingConfig = self::SHIPPING_STEP.'.children.shippingAddress.component';
            $paymentConfig = self::BILLING_STEP.'.children.payment.children.payments-list.component';
            $data->setArray($shippingConfig, 'Mavenbird_OneStepCheckout/js/view/shipping');
            $data->setArray($paymentConfig, 'Mavenbird_OneStepCheckout/js/view/payment/list');
        }
    }

    /**
     * FixedStorePickup
     *
     * @param AccessData $data
     * @return void
     */
    private function FixedStorePickup(AccessData $data)
    {
        $shippingConfig = self::SHIPPING_STEP.'.children.shippingAddress.children.shippingAdditional';
        $shippingMethodConfig = self::SHIPPING_METHOD.'.children.shippingAdditional';
        if ($data->hasArray($shippingConfig)) {
            $data->setArray($shippingMethodConfig, $data->getArray($shippingConfig));
        }
    }

    /**
     * GetDeliveryLayout
     *
     * @return void
     */
    private function getDeliveryLayout()
    {
        $layout = [
            'component' => 'uiComponent',
            'displayArea' => 'mdosc-delivery-date',
            'children' => [
                'delivery_date' => [
                    'component' => 'Mavenbird_OneStepCheckout/js/view/delivery-date',
                    'displayArea' => 'delivery-date-block',
                    'deps' => 'checkoutProvider',
                    'dataScopePrefix' => 'delivery_date',
                    'children' => [
                        'form-fields' => [
                            'component' => 'uiComponent',
                            'displayArea' => 'delivery-date-block',
                            'children' => [
                                'md_osc_delivery_date' => [
                                    'component' => 'Magento_Ui/js/form/element/abstract',
                                    'config' => [
                                        'customScope' => 'delivery_date',
                                        'template' => 'ui/form/element/hidden',
                                        'id' => 'md_osc_delivery_date'
                                    ],
                                    'dataScope' => 'delivery_date.md_osc_delivery_date',
                                    'provider' => 'checkoutProvider',
                                    'id' => 'md_osc_delivery_date'
                                ],
                                'md_osc_delivery_time' => [
                                    'component' => 'Magento_Ui/js/form/element/abstract',
                                    'config' => [
                                        'customScope' => 'delivery_date',
                                        'template' => 'ui/form/element/hidden',
                                        'id' => 'md_osc_delivery_time'
                                    ],
                                    'dataScope' => 'delivery_date.md_osc_delivery_time',
                                    'provider' => 'checkoutProvider',
                                    'id' => 'md_osc_delivery_time'
                                ],
                                'md_osc_delivery_comment' => [
                                    'component' => 'Magento_Ui/js/form/element/textarea',
                                    'config' => [
                                        'customScope' => 'delivery_date',
                                        'template' => 'ui/form/field',
                                        'elementTmpl' => 'ui/form/element/textarea',
                                        'options' => [],
                                        'id' => 'md_osc_delivery_comment'
                                    ],
                                    'dataScope' => 'delivery_date.md_osc_delivery_comment',
                                    'label' => '',
                                    'provider' => 'checkoutProvider',
                                    'visible' => (boolean) $this->oscHelper->isDeliveryDateComment(),
                                    'validation' => [],
                                    'sortOrder' => 20,
                                    'id' => 'md_osc_delivery_comment',
                                    'placeholder' => 'Leave your shipping comment'
                                ]
                            ],
                        ],
                    ]
                ]
            ]
        ];
        return $layout;
    }
}
