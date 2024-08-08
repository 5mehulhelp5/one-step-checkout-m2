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

namespace Mavenbird\OneStepCheckout\Plugin\Magento\Backend\Model\Menu;

class Item
{
    /**
     * AfterGetUrl
     *
     * @param [type] $subject
     * @param [type] $result
     * @return void
     */
    public function afterGetUrl($subject, $result)
    {
        $menuId = $subject->getId();

        if ($menuId == 'Mavenbird_OneStepCheckout::documentation') {
            $result = 'http://docs.Mavenbird.com/display/MAG/One+Step+Checkout+-+Magento+2';
        }

        return $result;
    }
}
