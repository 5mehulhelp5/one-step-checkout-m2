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

namespace Mavenbird\OneStepCheckout\Plugin\Checkout\Block\Product;

class AbstractProduct
{

    /**
     * @var \Mavenbird\OneStepCheckout\Helper\Data
     */
    private $oscHelper;

    /**
     * Construct
     *
     * @param \Mavenbird\OneStepCheckout\Helper\Data $oscHelper
     */
    public function __construct(
        \Mavenbird\OneStepCheckout\Helper\Data $oscHelper
    ) {
        $this->oscHelper = $oscHelper;
    }

    /**
     * IsRedirectToCartEnabled
     *
     * @return boolean
     */
    public function isRedirectToCartEnabled()
    {
        return $this->_scopeConfig->getValue(
            'checkout/cart/redirect_to_cart',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * AfterisRedirectToCartEnabled
     *
     * @param \Magento\Catalog\Block\Product\AbstractProduct $subject
     * @param [type] $result
     * @return void
     */
    public function afterisRedirectToCartEnabled(\Magento\Catalog\Block\Product\AbstractProduct $subject, $result)
    {
        if ($this->oscHelper->allowRedirectCheckoutAfterProductAddToCart()) {
            return true;
        } else {
            return $result;
        }
    }
}
