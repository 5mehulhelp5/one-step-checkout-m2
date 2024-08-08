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

namespace Mavenbird\OneStepCheckout\Plugin;

use Magento\Framework\Controller\ResultFactory;
use Mavenbird\OneStepCheckout\Helper\Data;

class WishlistRedirectCheckout
{
    /**
     * Urls
     *
     * @var [type]
     */
    public $url;

    /**
     * ResultFactorys
     *
     * @var [type]
     */
    public $resultFactory;

    /**
     * ResourceConnections
     *
     * @var [type]
     */
    protected $resourceConnection;

    /**
     * HelperDatas
     *
     * @var [type]
     */
    protected $helperData;

    /**
     * Construct
     *
     * @param \Magento\Framework\UrlInterface $url
     * @param ResultFactory $resultFactory
     * @param \Magento\Framework\App\ResourceConnection $resourceConnection
     * @param Data $helperData
     */
    public function __construct(
        \Magento\Framework\UrlInterface $url,
        ResultFactory $resultFactory,
        \Magento\Framework\App\ResourceConnection $resourceConnection,
        Data $helperData
    ) {
        $this->url = $url;
        $this->resultFactory = $resultFactory;
        $this->resourceConnection = $resourceConnection;
        $this->helperData = $helperData;
    }

    /**
     * AfterExecute
     *
     * @param \Magento\Wishlist\Controller\Index\Cart $subject
     * @param [type] $resultRedirect
     * @return void
     */
    public function afterExecute(\Magento\Wishlist\Controller\Index\Cart $subject, $resultRedirect)
    {
         $isRedirectEnable = $this->helperData->allowRedirectCheckoutAfterProductAddToCart();
        if ($isRedirectEnable) {
            $item_id = $subject->getRequest()->getParam('item');
            $connection  = $this->resourceConnection->getConnection();
            $tableName   = $connection->getTableName('wishlist_item_option');
            $query = 'SELECT * FROM '.$tableName.' WHERE wishlist_item_id = '.$item_id.' ';

            $results = $this->resourceConnection->getConnection()->fetchAll($query);
            if (empty($results)) {
                $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
                 $redirectUrl = $this->url->getUrl('checkout', ['_secure' => true]);
                 $resultRedirect->setUrl($redirectUrl);
            }
        }
         return $resultRedirect;
    }
}
