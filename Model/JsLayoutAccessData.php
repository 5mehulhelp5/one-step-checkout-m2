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

namespace Mavenbird\OneStepCheckout\Model;

class JsLayoutAccessData
{
    /**
     * @var array
     */
    protected $data;

    /**
     * JsLayoutAccessData constructor.
     * @param array|null $data
     */
    public function __construct(
        array $data = null
    ) {
        $this->data = $data ?: [];
    }

    /**
     * SetArray
     *
     * @param [type] $key
     * @param [type] $value
     * @return void
     */
    public function setArray($key, $value = null)
    {
        if (strlen($key) == 0) {
            throw new \RuntimeException(
                "Key cannot be an empty string"
            );
        }

        $currentValue =& $this->data;
        $keyPath = explode('.', $key);

        if (count($keyPath) == 1) {
            $currentValue[$key] = $value;

            return;
        }

        $endKey = array_pop($keyPath);
        for ($i = 0; $i < count($keyPath); $i++) {
            $currentKey =& $keyPath[$i];
            if (!isset($currentValue[$currentKey])) {
                $currentValue[$currentKey] = [];
            }
            if (!is_array($currentValue[$currentKey])) {
                throw new \RuntimeException(
                    "Key path at $currentKey of $key cannot be indexed into (is not an array)"
                );
            }
            $currentValue =& $currentValue[$currentKey];
        }
        $currentValue[$endKey] = $value;
    }

    /**
     * RemoveArray
     *
     * @param [type] $key
     * @return void
     */
    public function removeArray($key)
    {
        if (strlen($key) == 0) {
            throw new \RuntimeException("Key cannot be an empty string");
        }

        $currentValue =& $this->data;
        $keyPath = explode('.', $key);

        if (count($keyPath) == 1) {
            unset($currentValue[$key]);

            return;
        }

        $endKey = array_pop($keyPath);
        for ($i = 0; $i < count($keyPath); $i++) {
            $currentKey =& $keyPath[$i];
            if (!isset($currentValue[$currentKey])) {
                return;
            }
            $currentValue =& $currentValue[$currentKey];
        }
        unset($currentValue[$endKey]);
    }

    /**
     * GetArray
     *
     * @param [type] $key
     * @param [type] $default
     * @return void
     */
    public function getArray($key, $default = null)
    {
        $currentValue = $this->data;
        $keyPath = explode('.', $key);

        for ($i = 0; $i < count($keyPath); $i++) {
            $currentKey = $keyPath[$i];
            if (!isset($currentValue[$currentKey])) {
                return $default;
            }
            if (!is_array($currentValue)) {
                return $default;
            }
            $currentValue = $currentValue[$currentKey];
        }

        return $currentValue === null ? $default : $currentValue;
    }

    /**
     * HasArray
     *
     * @param [type] $key
     * @return boolean
     */
    public function hasArray($key)
    {
        $currentValue = &$this->data;
        $keyPath = explode('.', $key);

        for ($i = 0; $i < count($keyPath); $i++) {
            $currentKey = $keyPath[$i];
            if (!is_array($currentValue) ||
                !array_key_exists($currentKey, $currentValue)
            ) {
                return false;
            }
            $currentValue = &$currentValue[$currentKey];
        }

        return true;
    }

    /**
     * ExportArray
     *
     * @return void
     */
    public function exportArray()
    {
        return $this->data;
    }
}
