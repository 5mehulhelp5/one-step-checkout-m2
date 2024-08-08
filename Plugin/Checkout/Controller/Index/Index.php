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

namespace Mavenbird\OneStepCheckout\Plugin\Checkout\Controller\Index;

use Closure;
use Mavenbird\OneStepCheckout\Helper\Data;
use Magento\Checkout\Block\Onepage;
use Magento\Customer\Api\AccountManagementInterface;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Model\Session;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Controller\Result\RawFactory;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Data\Form\FormKey\Validator;
use Magento\Framework\Registry;
use Magento\Framework\Translate\InlineInterface;
use Magento\Framework\View\Result\LayoutFactory;
use Magento\Framework\View\Result\Page;
use Magento\Framework\View\Result\PageFactory;
use Magento\Quote\Api\CartRepositoryInterface;

class Index extends \Magento\Checkout\Controller\Index\Index
{
    public const ONE_STEP_CHECKOUT_LAYOUT = 'onestepcheckout';
    public const ONE_STEP_CHECKOUT_HEADER_FOOTER_LAYOUT = 'onestepcheckout_header_footer';
    public const ONE_STEP_CHECKOUT_EXTRA_FEE_LAYOUT = 'onestepcheckout_extrafee';

    /**
     * @var Data
     */
    private $oscHelper;

    /**
     * Construct
     *
     * @param Context $context
     * @param Session $customerSession
     * @param CustomerRepositoryInterface $customerRepository
     * @param AccountManagementInterface $accountManagement
     * @param Registry $coreRegistry
     * @param InlineInterface $translateInline
     * @param Validator $formKeyValidator
     * @param ScopeConfigInterface $scopeConfig
     * @param \Magento\Framework\View\LayoutFactory $layoutFactory
     * @param CartRepositoryInterface $quoteRepository
     * @param PageFactory $resultPageFactory
     * @param LayoutFactory $resultLayoutFactory
     * @param RawFactory $resultRawFactory
     * @param JsonFactory $resultJsonFactory
     * @param Data $oscHelper
     */
    public function __construct(
        Context $context,
        Session $customerSession,
        CustomerRepositoryInterface $customerRepository,
        AccountManagementInterface $accountManagement,
        Registry $coreRegistry,
        InlineInterface $translateInline,
        Validator $formKeyValidator,
        ScopeConfigInterface $scopeConfig,
        \Magento\Framework\View\LayoutFactory $layoutFactory,
        CartRepositoryInterface $quoteRepository,
        PageFactory $resultPageFactory,
        LayoutFactory $resultLayoutFactory,
        RawFactory $resultRawFactory,
        JsonFactory $resultJsonFactory,
        Data $oscHelper
    ) {
        parent::__construct(
            $context,
            $customerSession,
            $customerRepository,
            $accountManagement,
            $coreRegistry,
            $translateInline,
            $formKeyValidator,
            $scopeConfig,
            $layoutFactory,
            $quoteRepository,
            $resultPageFactory,
            $resultLayoutFactory,
            $resultRawFactory,
            $resultJsonFactory
        );
        $this->oscHelper = $oscHelper;
    }

    /**
     * AfterExecute
     *
     * @param \Magento\Checkout\Controller\Index\Index $subject
     * @param [type] $result
     * @return void
     */
    public function afterExecute(\Magento\Checkout\Controller\Index\Index $subject, $result)
    {
        if ($this->oscHelper->isModuleEnable()) {
            $resultPage = $this->resultPageFactory->create();
            $resultPage->getLayout()->getUpdate()->addHandle(self::ONE_STEP_CHECKOUT_HEADER_FOOTER_LAYOUT);
            if ($this->oscHelper->isExtraFeeEnabled()) {
                $resultPage->getLayout()->getUpdate()->addHandle(self::ONE_STEP_CHECKOUT_EXTRA_FEE_LAYOUT);
            }
            $resultPage->getLayout()->getUpdate()->addHandle(self::ONE_STEP_CHECKOUT_LAYOUT);
            $this->showHeaderFooter($resultPage->getLayout());
            // Meta Title
            $title = $this->oscHelper->getCheckoutMetaTitle() ? $this->oscHelper->getCheckoutMetaTitle() : 'Checkout';
            /** @var Onepage $checkoutBlock */
            $checkoutBlock = $resultPage->getLayout()->getBlock('checkout.root');
            $checkoutBlock->setTemplate('Mavenbird_OneStepCheckout::onestepcheckout.phtml')
                ->setData('osc_helper', $this->oscHelper);

            $resultPage->getConfig()->getTitle()->set(__($title));
            return $resultPage;
        } else {
            return $result;
        }
    }

    /**
     * ShowHeaderFooter
     *
     * @param [type] $layout
     * @return void
     */
    private function showHeaderFooter($layout)
    {
        if ($this->oscHelper->showHeader()) {
            $layout->getUpdate()->addHandle('onestepcheckout_show_header');
            if (!$this->oscHelper->showFooter()) {
                $layout->unsetElement('footer-container');
            }
        } else {
            if ($this->oscHelper->showFooter()) {
                $layout->getUpdate()->addHandle('onestepcheckout_show_footer');
            }
        }
    }
}
