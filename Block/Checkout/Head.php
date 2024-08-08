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

namespace Mavenbird\OneStepCheckout\Block\Checkout;

use Magento\Framework\View\Element\Template;
use Mavenbird\OneStepCheckout\Helper\Data;

class Head extends Template
{
    /**
     * @var Data
     */
    protected $helper;

    /**
     * Construct
     *
     * @param Template\Context $context
     * @param Data $helper
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        Data $helper,
        array $data
    ) {
        parent::__construct($context, $data);
        $this->helper = $helper;
    }

    /**
     * GetLoader
     *
     * @return void
     */
    public function getLoader()
    {
        $path = 'Mavenbird_OneStepCheckout/images/';
        $image = 'loader-1.gif';
        return $this->getViewFileUrl($path.$image);
    }

    /**
     * GetBlockLoader
     *
     * @return void
     */
    public function getBlockLoader()
    {
        $path = 'Mavenbird_OneStepCheckout/images/';
        $image = 'block-loader-1.gif';
        return $this->getViewFileUrl($path.$image);
    }

    /**
     * GetConfigColor
     *
     * @param [type] $name
     * @return void
     */
    public function getConfigColor($name)
    {
        switch ($name) {
            case 'heading':
                $color = $this->helper->getHeadingColor();
                break;
            case 'description':
                $color = $this->helper->getDescriptionColor();
                break;
            case 'step':
                $color = $this->helper->getStepsFontColor();
                break;
            case 'layout':
                $color = $this->helper->getLayoutColor();
                break;
            case 'orderButton':
                $color = $this->helper->getOrderButtonColor();
                break;
            default:
                $color = '#000000';
                break;
        }
        return $color;
    }
}
