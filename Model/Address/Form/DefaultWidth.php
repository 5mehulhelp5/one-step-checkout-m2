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

class DefaultWidth
{
    /**
     * @var $defaultWidth
     */
    protected $defaultWidth;

    /**
     * Construct
     *
     * @param [type] $defaultWidth
     */
    public function __construct(
        $defaultWidth
    ) {
        $this->defaultWidth = $defaultWidth;
    }

    /**
     * Getdefaultfieldwidth
     *
     * @param string $rowId
     * @return int|null
     */
    public function getDefaultWidth($rowId)
    {
        return isset($this->defaultWidth[$rowId])
            ? $this->defaultWidth[$rowId]
            : 100;
    }
}
