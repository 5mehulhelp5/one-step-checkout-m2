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

namespace Mavenbird\OneStepCheckout\Api;

use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Sales\Api\Data\OrderInterface;

interface RegistrationInterface
{
    /**
     * CreateUser
     *
     * @param [type] $order
     * @return void
     */
    public function createUser($order);

    /**
     * SaveHashToken
     *
     * @param [type] $cartId
     * @param [type] $token
     * @return void
     */
    public function saveHashToken($cartId, $token);

    /**
     * DeleteHashToken
     *
     * @param [type] $order
     * @return void
     */
    public function deleteHashToken($order);
}
