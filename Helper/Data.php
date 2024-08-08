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

namespace Mavenbird\OneStepCheckout\Helper;

use Magento\Checkout\Model\Session;
use Magento\Store\Model\ScopeInterface;
use Magento\Customer\Model\AccountManagement;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    public const XML_PATH_ONESTEPCHECKOUT_ACTIVE = 'onestepcheckout/general/active';
    public const XML_PATH_ONESTEPCHECKOUT_TITLE = 'onestepcheckout/general/checkout_title';
    public const XML_PATH_ONESTEPCHECKOUT_META_TITLE = 'onestepcheckout/general/checkout_meta_title';
    public const XML_PATH_ONESTEPCHECKOUT_DESCRIPTION = 'onestepcheckout/general/checkout_description';
    public const XML_PATH_ONESTEPCHECKOUT_EDIT_PRODUCT = 'onestepcheckout/general/edit_product';
    public const XML_PATH_ONESTEPCHECKOUT_SUGGEST_ADDRESS = 'onestepcheckout/general/suggest_address';
    public const XML_PATH_ONESTEPCHECKOUT_GOOGLE_API_KEY = 'onestepcheckout/general/google_api_key';
    public const XML_PATH_ONESTEPCHECKOUT_REDIRECT_TO_CHECKOUT = 'onestepcheckout/general/redirect_to_checkout';
    public const XML_PATH_ONESTEPCHECKOUT_REGISTRATION = 'onestepcheckout/general/registration';
    public const XML_PATH_ONESTEPCHECKOUT_AUTO_REGISTRATION = 'onestepcheckout/general/auto_registration';

    public const XML_PATH_ONESTEPCHECKOUT_SHOW_HEADER = 'onestepcheckout/display/display_header';
    public const XML_PATH_ONESTEPCHECKOUT_SHOW_FOOTER = 'onestepcheckout/display/display_footer';
    public const XML_PATH_ONESTEPCHECKOUT_SHOW_COMMENTS = 'onestepcheckout/display/display_comments';
    public const XML_PATH_ONESTEPCHECKOUT_SHOW_NEWSLETTER = 'onestepcheckout/display/display_newsletter';
    public const XML_PATH_ONESTEPCHECKOUT_DEFAULT_NEWSLETTER_CHECKED = 'onestepcheckout/display/default_newsletter_checked';
    public const XML_PATH_ONESTEPCHECKOUT_SHOW_DISCOUNT_COUPON = 'onestepcheckout/display/display_coupon';
    public const XML_PATH_ONESTEPCHECKOUT_SHOW_PRODUCT_THUMBNAIL = 'onestepcheckout/display/display_product_thumbnail';
    public const XML_PATH_ONESTEPCHECKOUT_SHOW_AGREEMENTS = 'onestepcheckout/display/display_agreements';
    public const XML_PATH_ONESTEPCHECKOUT_SHOW_TOP_CMS_BLOCK = 'onestepcheckout/display/checkout_header_block';
    public const XML_PATH_ONESTEPCHECKOUT_SHOW_BOTTOM_CMS_BLOCK = 'onestepcheckout/display/checkout_footer_block';
    public const XML_PATH_ONESTEPCHECKOUT_SUCCESS_TOP_CMS_BLOCK = 'onestepcheckout/display/success_header_block';
    public const XML_PATH_ONESTEPCHECKOUT_SUCCESS_BOTTOM_CMS_BLOCK = 'onestepcheckout/display/success_footer_block';

    public const XML_PATH_ONESTEPCHECKOUT_STYLE_HEADING_COLOR = 'onestepcheckout/display_style/heading_color';
    public const XML_PATH_ONESTEPCHECKOUT_STYLE_DESCRIPTION_COLOR = 'onestepcheckout/display_style/heading_description_color';
    public const XML_PATH_ONESTEPCHECKOUT_STYLE_LAYOUT_COLOR = 'onestepcheckout/display_style/steps_layout_color';
    public const XML_PATH_ONESTEPCHECKOUT_STYLE_FONT_COLOR = 'onestepcheckout/display_style/steps_font_color';
    public const XML_PATH_ONESTEPCHECKOUT_STYLE_ORDER_BUTTON_COLOR = 'onestepcheckout/display_style/order_button_color';

    public const XML_PATH_ONESTEPCHECKOUT_SHIPPING_FIELD_CUSTOMIZATION = 'onestepcheckout/shipping_field/shipping_fields_customization';
    public const XML_PATH_ONESTEPCHECKOUT_BILLING_FIELD_CUSTOMIZATION = 'onestepcheckout/billing_field/billing_fields_customization';
    public const SECTION_CONFIG_ONESTEPCHECKOUT = 'onestepcheckout';
    /**
     * Step Config Provider
     */
    public const XML_PATH_ONESTEPCHECKOUT_LAYOUT = 'onestepcheckout/step_config/layout';
    public const XML_PATH_ONESTEPCHECKOUT_STEP_CONFIG = 'onestepcheckout/step_config/customization';
    //const XML_PATH_ONESTEPCHECKOUT_REVIEW_LABEL = 'onestepcheckout/step_config/review_title';
    public const XML_PATH_ONESTEPCHECKOUT_BILLING_ADDRESS = 'onestepcheckout/step_config/billing_address';
    public const XML_PATH_ONESTEPCHECKOUT_PLACE_ORDER_POSITION = 'onestepcheckout/step_config/place_order_position';
    public const XML_PATH_CHECKOUT_BILLING_ADDRESS = 'checkout/options/display_billing_address_on';
    public const XML_PATH_ADDRESS_LINE = 'onestepcheckout/step_config/street_lines';
    public const XML_PATH_CUSTOMER_ADDRESS_LINE = 'customer/address/street_lines';
    /**
     * Delivery Date Config Provider
     */
    public const XML_PATH_ONESTEPCHECKOUT_DELIVERYDATE_ENABLED = 'onestepcheckout/delivery_date/enabled';
    public const XML_PATH_ONESTEPCHECKOUT_DELIVERYDATE_REQUIRED = 'onestepcheckout/delivery_date/required';
    public const XML_PATH_ONESTEPCHECKOUT_DELIVERYDATE_COMMENT = 'onestepcheckout/delivery_date/comment';
    public const XML_PATH_ONESTEPCHECKOUT_DELIVERYDATE_TIMESLOT = 'onestepcheckout/delivery_date/timeslot';
    public const XML_PATH_ONESTEPCHECKOUT_DELIVERYDATE_MININTERVAL = 'onestepcheckout/delivery_date/min_interval';
    public const XML_PATH_ONESTEPCHECKOUT_DELIVERYDATE_MAXINTERVAL = 'onestepcheckout/delivery_date/max_interval';
    /**
     * Extra Fee Config Provider
     */
    public const XML_PATH_ONESTEPCHECKOUT_EXTRAFEE_ENABLED = 'onestepcheckout/extra_fee/enabled';
    public const XML_PATH_ONESTEPCHECKOUT_EXTRAFEE = 'onestepcheckout/extra_fee/fee';
    public const XML_PATH_ONESTEPCHECKOUT_EXTRAFEE_LABEL = 'onestepcheckout/extra_fee/fee_title';
    public const XML_PATH_ONESTEPCHECKOUT_EXTRAFEE_CHECKBOX_TITLE = 'onestepcheckout/extra_fee/fee_checkbox_title';
    /**
     * Gift Message Config Provider
     */
    public const XML_PATH_ONESTEPCHECKOUT_GIFTMESSAGE_ORDER = 'onestepcheckout/gift_message/order_level';
    public const XML_PATH_ONESTEPCHECKOUT_GIFTMESSAGE_ITEM = 'onestepcheckout/gift_message/item_level';
    public const XPATH_CONFIG_GIFT_MESSAGE_ALLOW_ITEMS = 'sales/gift_options/allow_items';
    public const XPATH_CONFIG_GIFT_MESSAGE_ALLOW_ORDER = 'sales/gift_options/allow_order';

    /**
     * Default Shipping And Payment Method
     */
    public const XML_PATH_ONESTEPCHECKOUT_DEFAULT_SHIPPINGMETHOD = 'onestepcheckout/shipping_payment_method/shipping';
    public const XML_PATH_ONESTEPCHECKOUT_DEFAULT_PAYMENTMETHOD = 'onestepcheckout/shipping_payment_method/payment';

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    private $storeConfig;

    /**
     * @var \Magento\Framework\App\ProductMetadataInterface
     */
    private $productMetadata;

    /**
     * @var \Magento\Framework\Unserialize\Unserialize
     */
    protected $unserialize;

    /**
     * @var \Magento\Framework\Serialize\Serializer\Json
     */
    protected $json;

    /**
     * CheckoutSessions
     *
     * @var [type]
     */
    protected $checkoutSession;

    /**
     * StoreManagerInterface instance
     *
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    private $_storeManager;

    /**
     * Construct
     *
     * @param \Magento\Framework\App\Helper\Context $context
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Framework\App\ProductMetadataInterface $productMetadata
     * @param \Magento\Framework\Serialize\SerializerInterface $unserialize
     * @param \Magento\Framework\Serialize\Serializer\Json $json
     * @param Session $checkoutSession
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\App\ProductMetadataInterface $productMetadata,
        \Magento\Framework\Serialize\SerializerInterface $unserialize,
        \Magento\Framework\Serialize\Serializer\Json $json,
        Session $checkoutSession
    ) {
        $this->storeConfig = $context->getScopeConfig();
        $this->_storeManager = $storeManager;
        $this->productMetadata = $productMetadata;
        $this->unserialize = $unserialize;
        $this->json = $json;
        $this->checkoutSession = $checkoutSession;
        parent::__construct($context);
    }

    /**
     * GetQuote
     *
     * @return void
     */
    public function getQuote()
    {
        return $this->checkoutSession->getQuote();
    }

    /**
     * GetOneStepConfig
     *
     * @param [type] $relativePath
     * @return void
     */
    public function getOneStepConfig($relativePath)
    {
        return $this->scopeConfig->getValue(
            self::SECTION_CONFIG_ONESTEPCHECKOUT . '/' . $relativePath,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * IsModuleEnable
     *
     * @return boolean
     */
    public function isModuleEnable()
    {
        return $this->storeConfig->getValue(
            self::XML_PATH_ONESTEPCHECKOUT_ACTIVE,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * GetCheckoutTitle
     *
     * @return void
     */
    public function getCheckoutTitle()
    {
        return $this->storeConfig->getValue(
            self::XML_PATH_ONESTEPCHECKOUT_TITLE,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * GetCheckoutMetaTitle
     *
     * @return void
     */
    public function getCheckoutMetaTitle()
    {
        return $this->storeConfig->getValue(
            self::XML_PATH_ONESTEPCHECKOUT_META_TITLE,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * GetCheckoutDescription
     *
     * @return void
     */
    public function getCheckoutDescription()
    {
        return $this->storeConfig->getValue(
            self::XML_PATH_ONESTEPCHECKOUT_DESCRIPTION,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * GetCheckoutAddessSugetion
     *
     * @return void
     */
    public function getCheckoutAddessSugetion()
    {
        return $this->storeConfig->getValue(
            self::XML_PATH_ONESTEPCHECKOUT_SUGGEST_ADDRESS,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * GetCheckoutGoogleApiKey
     *
     * @return void
     */
    public function getCheckoutGoogleApiKey()
    {
        return $this->storeConfig->getValue(
            self::XML_PATH_ONESTEPCHECKOUT_GOOGLE_API_KEY,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * AllowRedirectCheckoutAfterProductAddToCart
     *
     * @return void
     */
    public function allowRedirectCheckoutAfterProductAddToCart()
    {
        return $this->storeConfig->getValue(
            self::XML_PATH_ONESTEPCHECKOUT_REDIRECT_TO_CHECKOUT,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * ShowComments
     *
     * @return void
     */
    public function showComments()
    {
        return $this->storeConfig->getValue(
            self::XML_PATH_ONESTEPCHECKOUT_SHOW_COMMENTS,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * ShowNewsletter
     *
     * @return void
     */
    public function showNewsletter()
    {
        return $this->storeConfig->getValue(
            self::XML_PATH_ONESTEPCHECKOUT_SHOW_NEWSLETTER,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * IsNewsletterChecked
     *
     * @return boolean
     */
    public function isNewsletterChecked()
    {
        return $this->storeConfig->getValue(
            self::XML_PATH_ONESTEPCHECKOUT_DEFAULT_NEWSLETTER_CHECKED,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * ShowDiscountCoupon
     *
     * @return void
     */
    public function showDiscountCoupon()
    {
        return $this->storeConfig->getValue(
            self::XML_PATH_ONESTEPCHECKOUT_SHOW_DISCOUNT_COUPON,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * ShowProductThumbnail
     *
     * @return void
     */
    public function showProductThumbnail()
    {
        return $this->storeConfig->getValue(
            self::XML_PATH_ONESTEPCHECKOUT_SHOW_PRODUCT_THUMBNAIL,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * ShowAgreements
     *
     * @return void
     */
    public function showAgreements()
    {
        return $this->storeConfig->getValue(
            self::XML_PATH_ONESTEPCHECKOUT_SHOW_AGREEMENTS,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * GetCmsBlockByArea
     *
     * @param [type] $area
     * @return void
     */
    public function getCmsBlockByArea($area)
    {
        if ($area == 'header') {
            return $this->storeConfig->getValue(
                self::XML_PATH_ONESTEPCHECKOUT_SHOW_TOP_CMS_BLOCK,
                ScopeInterface::SCOPE_STORE
            );
        } else {
            return $this->storeConfig->getValue(
                self::XML_PATH_ONESTEPCHECKOUT_SHOW_BOTTOM_CMS_BLOCK,
                ScopeInterface::SCOPE_STORE
            );
        }
    }

    /**
     * GetSuccessCmsBlockByArea
     *
     * @param [type] $area
     * @return void
     */
    public function getSuccessCmsBlockByArea($area)
    {
        if ($area == 'header') {
            return $this->storeConfig->getValue(
                self::XML_PATH_ONESTEPCHECKOUT_SUCCESS_TOP_CMS_BLOCK,
                ScopeInterface::SCOPE_STORE
            );
        } else {
            return $this->storeConfig->getValue(
                self::XML_PATH_ONESTEPCHECKOUT_SUCCESS_BOTTOM_CMS_BLOCK,
                ScopeInterface::SCOPE_STORE
            );
        }
    }

    /**
     * GetHeadingColor
     *
     * @return void
     */
    public function getHeadingColor()
    {
        return $this->storeConfig->getValue(
            self::XML_PATH_ONESTEPCHECKOUT_STYLE_HEADING_COLOR,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * GetDescriptionColor
     *
     * @return void
     */
    public function getDescriptionColor()
    {
        return $this->storeConfig->getValue(
            self::XML_PATH_ONESTEPCHECKOUT_STYLE_DESCRIPTION_COLOR,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * GetStepsFontColor
     *
     * @return void
     */
    public function getStepsFontColor()
    {
        return $this->storeConfig->getValue(
            self::XML_PATH_ONESTEPCHECKOUT_STYLE_FONT_COLOR,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * GetLayoutColor
     *
     * @return void
     */
    public function getLayoutColor()
    {
        return $this->storeConfig->getValue(
            self::XML_PATH_ONESTEPCHECKOUT_STYLE_LAYOUT_COLOR,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * GetOrderButtonColor
     *
     * @return void
     */
    public function getOrderButtonColor()
    {
        return $this->storeConfig->getValue(
            self::XML_PATH_ONESTEPCHECKOUT_STYLE_ORDER_BUTTON_COLOR,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * GetShippingAddressFieldConfig
     *
     * @return void
     */
    public function getShippingAddressFieldConfig()
    {
        $value = $this->scopeConfig->getValue(
            self::XML_PATH_ONESTEPCHECKOUT_SHIPPING_FIELD_CUSTOMIZATION,
            ScopeInterface::SCOPE_STORE
        );
        //return unserialize($value);
        return $value ? $this->unserialize->unserialize($value) : [];
    }

    /**
     * GetBillingAddressFieldConfig
     *
     * @return void
     */
    public function getBillingAddressFieldConfig()
    {
        $value = $this->scopeConfig->getValue(
            self::XML_PATH_ONESTEPCHECKOUT_BILLING_FIELD_CUSTOMIZATION,
            ScopeInterface::SCOPE_STORE
        );
        return $value ? $this->unserialize->unserialize($value) : [];
    }

    /**
     * GetStepConfig
     *
     * @return void
     */
    public function getStepConfig()
    {
        $steps = [];
        $value = $this->scopeConfig->getValue(
            self::XML_PATH_ONESTEPCHECKOUT_STEP_CONFIG,
            ScopeInterface::SCOPE_STORE
        );
        if ($value) {
            $steps = $this->unserialize->unserialize($value);
            if ($this->getQuote()->isVirtual()) {
                if ($steps['rows']['payment']['sort_order'] < $steps['rows']['review']['sort_order']) {
                    $steps['rows']['payment']['sort_order'] = '0';
                    $steps['rows']['review']['sort_order'] = '1';
                } else {
                    $steps['rows']['payment']['sort_order'] = '1';
                    $steps['rows']['review']['sort_order'] = '0';
                }
            }
        }
        return $steps;
    }

    /**
     * GetBillingAddressBlock
     *
     * @return void
     */
    public function getBillingAddressBlock()
    {
        if ($this->getQuote()->isVirtual()) {
            return 'payment';
        }
        return $this->storeConfig->getValue(
            self::XML_PATH_ONESTEPCHECKOUT_BILLING_ADDRESS,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * GetPlaceOrderPosition
     *
     * @return void
     */
    public function getPlaceOrderPosition()
    {
        return $this->storeConfig->getValue(
            self::XML_PATH_ONESTEPCHECKOUT_PLACE_ORDER_POSITION,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * GetAddressLine
     *
     * @return void
     */
    public function getAddressLine()
    {
        $line = $this->storeConfig->getValue(
            self::XML_PATH_ADDRESS_LINE,
            ScopeInterface::SCOPE_STORE
        );
        if ($line) {
            return $line;
        }
        return $this->storeConfig->getValue(
            self::XML_PATH_CUSTOMER_ADDRESS_LINE,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * GetLayout
     *
     * @return void
     */
    public function getLayout()
    {
        if ($this->getQuote()->isVirtual()) {
            return '2column';
        }
        return $this->storeConfig->getValue(
            self::XML_PATH_ONESTEPCHECKOUT_LAYOUT,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * GetLayoutClass
     *
     * @return void
     */
    public function getLayoutClass()
    {
        $layout = $this->getLayout();
        if ($layout == '3column') {
            return 'layout-3columns-osc';
        } else {
            return 'layout-2columns-osc';
        }
    }

    /**
     * IsEditProductAllowed
     *
     * @return boolean
     */
    public function isEditProductAllowed()
    {
        return $this->storeConfig->getValue(
            self::XML_PATH_ONESTEPCHECKOUT_EDIT_PRODUCT,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * IsEnabled
     *
     * @return boolean
     */
    public function isEnabled()
    {
        return $this->getConfig('onestepcheckout/general/active');
    }

    /**
     * GetConfig
     *
     * @param [type] $config_path
     * @return void
     */
    public function getConfig($config_path)
    {
        return $this->scopeConfig->getValue(
            $config_path,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * IsVersionAbove
     *
     * @param [type] $patchVersion
     * @return boolean
     */
    public function isVersionAbove($patchVersion)
    {
        if ($this->isEnabled()) {
            $version = $this->productMetadata->getVersion();
            return version_compare($version, $patchVersion, '>=');
        }
        return false;
    }

    /**
     * IsDeliveryDateEnabled
     *
     * @return boolean
     */
    public function isDeliveryDateEnabled()
    {
        if ($this->isEnabled()) {
            return $this->storeConfig->getValue(
                self::XML_PATH_ONESTEPCHECKOUT_DELIVERYDATE_ENABLED,
                ScopeInterface::SCOPE_STORE
            );
        }
        return false;
    }

    /**
     * IsDeliveryDateRequired
     *
     * @return boolean
     */
    public function isDeliveryDateRequired()
    {
        return $this->storeConfig->getValue(
            self::XML_PATH_ONESTEPCHECKOUT_DELIVERYDATE_REQUIRED,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * IsDeliveryDateComment
     *
     * @return boolean
     */
    public function isDeliveryDateComment()
    {
        return $this->storeConfig->getValue(
            self::XML_PATH_ONESTEPCHECKOUT_DELIVERYDATE_COMMENT,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * GetDeliveryTimeSlot
     *
     * @return void
     */
    public function getDeliveryTimeSlot()
    {
        $value = $this->storeConfig->getValue(
            self::XML_PATH_ONESTEPCHECKOUT_DELIVERYDATE_TIMESLOT,
            ScopeInterface::SCOPE_STORE
        );
        if (empty($value)) {
            return false;
        }
        if ($this->isSerialized($value)) {
            $unserializer = $this->unserialize;
        } else {
            $unserializer = $this->json;
        }
        return $unserializer->unserialize($value);
    }

    /**
     * IsSerialized
     *
     * @param [type] $value
     * @return boolean
     */
    private function isSerialized($value)
    {
        return (boolean) preg_match('/^((s|i|d|b|a|O|C):|N;)/', $value);
    }

    /**
     * GetDeliveryMinInterval
     *
     * @return void
     */
    public function getDeliveryMinInterval()
    {
        return $this->storeConfig->getValue(
            self::XML_PATH_ONESTEPCHECKOUT_DELIVERYDATE_MININTERVAL,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * GetDeliveryMaxInterval
     *
     * @return void
     */
    public function getDeliveryMaxInterval()
    {
        return $this->storeConfig->getValue(
            self::XML_PATH_ONESTEPCHECKOUT_DELIVERYDATE_MAXINTERVAL,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * IsExtraFeeEnabled
     *
     * @return boolean
     */
    public function isExtraFeeEnabled()
    {
        return $this->storeConfig->getValue(
            self::XML_PATH_ONESTEPCHECKOUT_EXTRAFEE_ENABLED,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * GetExtraFee
     *
     * @return void
     */
    public function getExtraFee()
    {
        return $this->storeConfig->getValue(
            self::XML_PATH_ONESTEPCHECKOUT_EXTRAFEE,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * GetExtraFeeLabel
     *
     * @return void
     */
    public function getExtraFeeLabel()
    {
        return $this->storeConfig->getValue(
            self::XML_PATH_ONESTEPCHECKOUT_EXTRAFEE_LABEL,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * GetExtraFeeCheckboxLabel
     *
     * @return void
     */
    public function getExtraFeeCheckboxLabel()
    {
        return $this->storeConfig->getValue(
            self::XML_PATH_ONESTEPCHECKOUT_EXTRAFEE_CHECKBOX_TITLE,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * ShowHeader
     *
     * @return void
     */
    public function showHeader()
    {
        return $this->storeConfig->getValue(
            self::XML_PATH_ONESTEPCHECKOUT_SHOW_HEADER,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * ShowFooter
     *
     * @return void
     */
    public function showFooter()
    {
        return $this->storeConfig->getValue(
            self::XML_PATH_ONESTEPCHECKOUT_SHOW_FOOTER,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * GetDefaultShippingMethod
     *
     * @return void
     */
    public function getDefaultShippingMethod()
    {
        return $this->storeConfig->getValue(
            self::XML_PATH_ONESTEPCHECKOUT_DEFAULT_SHIPPINGMETHOD,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * GetDefaultPaymentMethod
     *
     * @return void
     */
    public function getDefaultPaymentMethod()
    {
        return $this->storeConfig->getValue(
            self::XML_PATH_ONESTEPCHECKOUT_DEFAULT_PAYMENTMETHOD,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * GetGiftMessageOrderLevel
     *
     * @return void
     */
    public function getGiftMessageOrderLevel()
    {
        return $this->storeConfig->getValue(
            self::XML_PATH_ONESTEPCHECKOUT_GIFTMESSAGE_ORDER,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * GetGiftMessageItemLevel
     *
     * @return void
     */
    public function getGiftMessageItemLevel()
    {
        return $this->storeConfig->getValue(
            self::XML_PATH_ONESTEPCHECKOUT_GIFTMESSAGE_ITEM,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * IsGiftMessageOrderLevel
     *
     * @return boolean
     */
    public function isGiftMessageOrderLevel()
    {
        $result = false;
        $osc = $this->storeConfig->getValue(
            self::XML_PATH_ONESTEPCHECKOUT_GIFTMESSAGE_ORDER,
            ScopeInterface::SCOPE_STORE
        );
        $core = $this->storeConfig->getValue(
            self::XPATH_CONFIG_GIFT_MESSAGE_ALLOW_ORDER,
            ScopeInterface::SCOPE_STORE
        );
        if ($core) {
            $osc ? $result = true : $result = false;
        }
        return $result;
    }

    /**
     * IsGiftMessageItemLevel
     *
     * @return boolean
     */
    public function isGiftMessageItemLevel()
    {
        $result = false;
        $osc = $this->storeConfig->getValue(
            self::XML_PATH_ONESTEPCHECKOUT_GIFTMESSAGE_ITEM,
            ScopeInterface::SCOPE_STORE
        );
        $core = $this->storeConfig->getValue(
            self::XPATH_CONFIG_GIFT_MESSAGE_ALLOW_ITEMS,
            ScopeInterface::SCOPE_STORE
        );
        if ($core) {
            $osc ? $result = true : $result = false;
        }
        return $result;
    }

    /**
     * GetRequiredPasswordCharacter
     *
     * @return void
     */
    public function getRequiredPasswordCharacter()
    {
        return $this->storeConfig->getValue(
            AccountManagement::XML_PATH_REQUIRED_CHARACTER_CLASSES_NUMBER,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * GetMinimumPasswordLength
     *
     * @return void
     */
    public function getMinimumPasswordLength()
    {
        return $this->storeConfig->getValue(
            AccountManagement::XML_PATH_MINIMUM_PASSWORD_LENGTH,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * IsRegistrationEnabled
     *
     * @return boolean
     */
    public function isRegistrationEnabled()
    {
        return $this->storeConfig->getValue(
            self::XML_PATH_ONESTEPCHECKOUT_REGISTRATION,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * IsAutoRegistrationEnabled
     *
     * @return boolean
     */
    public function isAutoRegistrationEnabled()
    {
        if ($this->isRegistrationEnabled()) {
            return false;
        }
        return $this->storeConfig->getValue(
            self::XML_PATH_ONESTEPCHECKOUT_AUTO_REGISTRATION,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * IsPayPalContext
     *
     * @param [type] $storeId
     * @return boolean
     */
    public function isPayPalContext($storeId = null)
    {
        return $this->scopeConfig->isSetFlag(
            'payment/paypal_express/in_context',
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }
}
