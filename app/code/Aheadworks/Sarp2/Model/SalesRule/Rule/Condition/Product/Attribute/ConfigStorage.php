<?php
/**
 * Aheadworks Inc.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://aheadworks.com/end-user-license-agreement/
 *
 * @package    Sarp2
 * @version    2.15.3
 * @copyright  Copyright (c) 2021 Aheadworks Inc. (https://aheadworks.com/)
 * @license    https://aheadworks.com/end-user-license-agreement/
 */
namespace Aheadworks\Sarp2\Model\SalesRule\Rule\Condition\Product\Attribute;

/**
 * Class ConfigStorage
 *
 * @package Aheadworks\Sarp2\Model\SalesRule\Rule\Condition\Product\Attribute
 */
class ConfigStorage
{
    /**
     * @var array
     */
    private $configData;

    /**
     * @param array $configData
     */
    public function __construct(
        array $configData = []
    ) {
        $this->configData = $configData;
    }

    /**
     * Retrieve attribute label
     *
     * @param string $attributeCode
     * @return string
     */
    public function getLabel($attributeCode)
    {
        $attributeConfig = $this->getAttributeConfig($attributeCode);
        $label = $attributeConfig['label'] ?? '';
        return (string)__($label);
    }

    /**
     * Retrieve attribute input type
     *
     * @param string $attributeCode
     * @return string
     */
    public function getInputType($attributeCode)
    {
        $attributeConfig = $this->getAttributeConfig($attributeCode);
        return $attributeConfig['inputType'] ?? '';
    }

    /**
     * Retrieve attribute value element type
     *
     * @param string $attributeCode
     * @return string
     */
    public function getValueElementType($attributeCode)
    {
        $attributeConfig = $this->getAttributeConfig($attributeCode);
        return $attributeConfig['valueElementType'] ?? '';
    }

    /**
     * Retrieve attribute select option list
     *
     * @param string $attributeCode
     * @return array
     */
    public function getSelectOptionList($attributeCode)
    {
        $attributeConfig = $this->getAttributeConfig($attributeCode);
        $selectOptionList = $attributeConfig['selectOptionList'] ?? [];
        foreach ($selectOptionList as &$option) {
            if (isset($option['label'])) {
                $option['label'] = __($option['label']);
            }
        }
        return $selectOptionList;
    }

    /**
     * Retrieve config for the attribute by its code
     *
     * @param string $attributeCode
     * @return array
     */
    protected function getAttributeConfig($attributeCode)
    {
        return isset($this->configData[$attributeCode]) && is_array($this->configData[$attributeCode])
            ? $this->configData[$attributeCode]
            : [];
    }
}
