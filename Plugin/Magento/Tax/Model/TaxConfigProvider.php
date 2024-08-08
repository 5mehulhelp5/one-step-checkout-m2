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

namespace Mavenbird\OneStepCheckout\Plugin\Magento\Tax\Model;

use Mavenbird\OneStepCheckout\Model\CountryByWebsite;

class TaxConfigProvider
{
    /**
     * Countrycodes
     *
     * @var [type]
     */
    protected $countrycode;

    /**
     * Construct
     *
     * @param \Mavenbird\OneStepCheckout\Helper\Data $helper
     * @param CountryByWebsite $countrycode
     */
    public function __construct(
        \Mavenbird\OneStepCheckout\Helper\Data $helper,
        CountryByWebsite $countrycode
    ) {
        $this->helper = $helper;
        $this->countrycode = $countrycode;
    }

    /**
     * AfterGetConfig
     *
     * @param [type] $subject
     * @param [type] $result
     * @return void
     */
    public function afterGetConfig($subject, $result)
    {
        if (!$this->helper->isModuleEnable()) {
            return $result;
        }

        if ($this->helper->getShippingAddressFieldConfig()) {
            $config = $this->helper->getShippingAddressFieldConfig();
            if (isset($config['rows']['country_id']['default'])) {
                if ($config['rows']['country_id']['default']) {
                    if ($config['rows']['country_id']['default'] == 'selected="selected"') {
                        $coreValue = $this->countrycode->getCountryByWebsite();
                        $result['defaultCountryId'] = $coreValue;
                    } else {
                        $result['defaultCountryId'] = $config['rows']['country_id']['default'];
                    }
                    
                }
            }

            if (isset($config['rows']['region_id']['default'])) {
                if ($config['rows']['region_id']['default']) {
                    $result['defaultRegionId'] = $config['rows']['region_id']['default'];
                }
            }

            if (isset($config['rows']['postcode']['default'])) {
                if ($config['rows']['postcode']['default']) {
                    $result['defaultPostcode'] = $config['rows']['postcode']['default'];
                }
            }
        }
        return $result;
    }
}
