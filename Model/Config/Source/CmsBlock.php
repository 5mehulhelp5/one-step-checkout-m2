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

namespace Mavenbird\OneStepCheckout\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;
use Magento\Cms\Api\BlockRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaInterface;

class CmsBlock implements OptionSourceInterface
{
    /**
     * @var BlockRepositoryInterface
     */
    private $blockRepository;

    /**
     * @var SearchCriteriaInterface
     */
    private $searchCriteria;

    /**
     * Construct
     *
     * @param BlockRepositoryInterface $blockRepository
     * @param SearchCriteriaInterface $searchCriteria
     */
    public function __construct(
        BlockRepositoryInterface $blockRepository,
        SearchCriteriaInterface $searchCriteria
    ) {
        $this->blockRepository = $blockRepository;
        $this->searchCriteria = $searchCriteria;
    }

    /**
     * ToOptionArray
     *
     * @return void
     */
    public function toOptionArray()
    {
        $responseArray = [];
        $default = [
            'value' => 0,
            'label' => __('---- Please select a static block ----')
        ];

        $allItems = $this->blockRepository->getList($this->searchCriteria)->getItems();

        foreach ($allItems as $item) {
            $responseArray[] = [
                'value' => $item->getIdentifier(),
                'label' => __($item->getTitle())
            ];
        }
        array_unshift($responseArray, $default);
        return $responseArray;
    }
}
