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

namespace Mavenbird\OneStepCheckout\Block\Adminhtml\System\Config;

use Magento\Config\Block\System\Config\Form\Field;
use Magento\Framework\Data\Form\Element\AbstractElement;
use Magento\Backend\Block\Template\Context;
use Magento\Customer\Api\AddressMetadataInterface;
use Mavenbird\OneStepCheckout\Model\Address\Form\DefaultSortOrder;
use Mavenbird\OneStepCheckout\Model\Address\Form\DefaultWidth;
use Magento\Directory\Model\Config\Source\Country;

class ShippingAddress extends Field
{

    /**
     * Templates
     *
     * @var string
     */
    protected $_template = 'system/config/address.phtml';

    /**
     * @var AddressMetadataInterface
     */
    protected $addressMetadata;

    /**
     * @var DefaultSortOrder
     */
    protected $defaultSortOrder;

    /**
     * @var DefaultWidth
     */
    protected $defaultWidth;

    /**
     * @var Country
     */
    protected $country;

    /**
     * Construct
     *
     * @param Context $context
     * @param AddressMetadataInterface $addressMetadata
     * @param DefaultSortOrder $defaultSortOrder
     * @param DefaultWidth $defaultWidth
     * @param Country $country
     * @param array $data
     */
    public function __construct(
        Context $context,
        AddressMetadataInterface $addressMetadata,
        DefaultSortOrder $defaultSortOrder,
        DefaultWidth $defaultWidth,
        Country $country,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->addressMetadata = $addressMetadata;
        $this->defaultSortOrder = $defaultSortOrder;
        $this->defaultWidth = $defaultWidth;
        $this->country = $country;
    }

    /**
     * GetElementHtml
     *
     * @param AbstractElement $element
     * @return void
     */
    protected function _getElementHtml(AbstractElement $element)
    {
        $this->setElement($element);
        return $this->_toHtml();
    }

    /**
     * GetAllAddressAttributes
     *
     * @return void
     */
    private function getAllAddressAttributes()
    {
        $allAttributes = $this->addressMetadata->getAttributes('customer_register_address');
        return $allAttributes;
    }

    /**
     * GetAttributesFieldsFilterByCode
     *
     * @param [type] $code
     * @return void
     */
    private function getAttributesFieldsFilterByCode($code)
    {
        $allAttributes = $this->getAllAddressAttributes();
        $data = $allAttributes[$code];
        return $data;
    }

    /**
     * GetAllAddressAttributesCodes
     *
     * @return void
     */
    private function getAllAddressAttributesCodes()
    {
        $allAttributes = $this->getAllAddressAttributes();
        foreach ($allAttributes as $attribute) {
            $code[] = $attribute->getAttributeCode();
        }
        return $code;
    }

    /**
     * GetSortOrder
     *
     * @param [type] $code
     * @return void
     */
    private function getSortOrder($code)
    {
        $configValue = $this->getElement()->getValue();
        if (isset($configValue['rows'][$code])) {
            $sortOrder = isset($configValue['rows'][$code]['sort_order']) ?
                $configValue['rows'][$code]['sort_order'] :
                $this->defaultSortOrder->getSortOrder($code);
        } else {
            $sortOrder = $this->defaultSortOrder->getSortOrder($code);
        }
        return $sortOrder;
    }

    /**
     * GetWidth
     *
     * @param [type] $code
     * @return void
     */
    private function getWidth($code)
    {
        $configValue = $this->getElement()->getValue();
        if (isset($configValue['rows'][$code])) {
            $width = isset($configValue['rows'][$code]['width']) ?
                $configValue['rows'][$code]['width'] :
                $this->defaultWidth->getDefaultWidth($code);
        } else {
            $width = $this->defaultWidth->getDefaultWidth($code);
        }
        return $width;
    }

    /**
     * GetFrontendLabel
     *
     * @param [type] $code
     * @return void
     */
    private function getFrontendLabel($code)
    {
        $configValue = $this->getElement()->getValue();
        if (isset($configValue['rows'][$code]['label'])) {
            $label = $configValue['rows'][$code]['label'];
        } else {
            $label = $this->getAttributesFieldsFilterByCode($code)->getFrontendLabel();
        }
        return $label;
    }

    /**
     * IsRequired
     *
     * @param [type] $code
     * @return boolean
     */
    private function isRequired($code)
    {
        $configValue = $this->getElement()->getValue();
        if (isset($configValue['rows'][$code])) {
            $required = isset($configValue['rows'][$code]['required']) ?
                $configValue['rows'][$code]['required'] :
                0;
        } else {
            $required = $this->getAttributesFieldsFilterByCode($code)->isRequired();
        }
        return $required;
    }

    /**
     * IsVisible
     *
     * @param [type] $code
     * @return boolean
     */
    private function isVisible($code)
    {
        $configValue = $this->getElement()->getValue();
        if (isset($configValue['rows'][$code])) {
            $required = isset($configValue['rows'][$code]['visible']) ?
                $configValue['rows'][$code]['visible'] :
                0;
        } else {
            $required = $this->getAttributesFieldsFilterByCode($code)->isVisible();
        }
        return $required;
    }

    /**
     * GetDefaultValue
     *
     * @param [type] $code
     * @return void
     */
    private function getDefaultValue($code)
    {
        $configValue = $this->getElement()->getValue();
        if (isset($configValue['rows'][$code])) {
            $required = isset($configValue['rows'][$code]['default']) ?
                $configValue['rows'][$code]['default'] :
                0;
        } else {
            $required = $this->getAttributesFieldsFilterByCode($code)->getDefaultValue();
        }
        return $required;
    }

    /**
     * GetAdditionalClass
     *
     * @param [type] $code
     * @return void
     */
    private function getAdditionalClass($code)
    {
        $configValue = $this->getElement()->getValue();
        if (isset($configValue['rows'][$code])) {
            $required = isset($configValue['rows'][$code]['additional_class']) ?
                $configValue['rows'][$code]['additional_class'] :
                '';
        } else {
            $required = '';
        }
        return $required;
    }

    /**
     * MakeAddressFieldArray
     *
     * @return void
     */
    public function makeAddressFieldArray()
    {
        $fieldArray = [];
        $codes = $this->getAllAddressAttributesCodes();
        foreach ($codes as $code) {
            $this->defaultWidth->getDefaultWidth($code);
            if ($code != 'region') {
                $fieldArray[] = [
                    'code' => $code,
                    'sort_order' => $this->getSortOrder($code),
                    'label' => $this->getFrontendLabel($code),
                    'required' => $this->isRequired($code),
                    'visible' => $this->isVisible($code),
                    'width' => $this->getWidth($code),
                    'default' => $this->getDefaultValue($code),
                    'additional_class' => $this->getAdditionalClass($code),
                ];
            }
        }
        array_multisort(
            array_column($fieldArray, 'sort_order'),
            SORT_ASC,
            $fieldArray
        );

        return $fieldArray;
    }

    /**
     * GetCountryValue
     *
     * @return void
     */
    public function getCountryValue()
    {
        return $this->getAttributesFieldsFilterByCode('country_id')->getOptions();
        //return $this->country->toOptionArray();
    }

    /**
     * GetRegionValue
     *
     * @return void
     */
    public function getRegionValue()
    {
        return $this->getAttributesFieldsFilterByCode('region_id')->getOptions();
    }
}
