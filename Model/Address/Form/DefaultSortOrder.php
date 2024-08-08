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

namespace Mavenbird\OneStepCheckout\Model\Address\Form;

class DefaultSortOrder
{
    /**
     * @var $defaultSortOrder
     */
    protected $defaultSortOrder;

    /**
     * Construct
     *
     * @param [type] $defaultSortOrder
     */
    public function __construct(
        $defaultSortOrder
    ) {
        $this->defaultSortOrder = $defaultSortOrder;
    }

    /**
     * GetSortOrder
     *
     * @param [type] $rowId
     * @return void
     */
    public function getSortOrder($rowId)
    {
        return isset($this->defaultSortOrder[$rowId])
            ? $this->defaultSortOrder[$rowId]
            : null;
    }

    /**
     * CalculateSortOrder
     *
     * @param [type] $rowId
     * @param [type] $previous
     * @return void
     */
    public function calculateSortOrder($rowId, $previous = null)
    {
        $sortOrder = $this->getSortOrder($rowId);
        if (!$sortOrder) {
            return $previous !== null
                ? $previous + 1
                : max($this->defaultSortOrder) + 1;
        }
        return $sortOrder;
    }
}
