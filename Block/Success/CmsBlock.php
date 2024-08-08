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

namespace Mavenbird\OneStepCheckout\Block\Success;

use Magento\Framework\View\Element\Context;
use Magento\Cms\Model\Template\FilterProvider;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Cms\Model\BlockFactory;
use Mavenbird\OneStepCheckout\Helper\Data;

/**
 * Class Block
 */
class CmsBlock extends \Magento\Cms\Block\Block
{
    /**
     * @var $blockId
     */
    private $blockId;

    /**
     * @var Data
     */
    private $helper;

    /**
     * Construct
     *
     * @param Context $context
     * @param FilterProvider $filterProvider
     * @param StoreManagerInterface $storeManager
     * @param BlockFactory $blockFactory
     * @param Data $helper
     * @param array $data
     */
    public function __construct(
        Context $context,
        FilterProvider $filterProvider,
        StoreManagerInterface $storeManager,
        BlockFactory $blockFactory,
        Data $helper,
        array $data = []
    ) {
        parent::__construct($context, $filterProvider, $storeManager, $blockFactory, $data);
        $this->helper = $helper;
    }

    /**
     * GetBlockId
     *
     * @return void
     */
    public function getBlockId()
    {
        if ($this->blockId === null && $this->helper->isEnabled()) {
            $this->blockId = $this->helper->getSuccessCmsBlockByArea($this->getArea());
        }
        return $this->blockId;
    }
}
