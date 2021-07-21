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
 * @version    2.15.0
 * @copyright  Copyright (c) 2021 Aheadworks Inc. (https://aheadworks.com/)
 * @license    https://aheadworks.com/end-user-license-agreement/
 */
namespace Aheadworks\Sarp2\Model\Product\Attribute\Backend;

use Magento\Catalog\Model\Product\Attribute\Source\Boolean as BooleanSource;

/**
 * Class BooleanWithConfig
 *
 * @package Aheadworks\Sarp2\Model\Product\Attribute\Backend
 */
class BooleanWithConfig extends \Magento\Eav\Model\Entity\Attribute\Backend\AbstractBackend
{
    /**
     * @inheritDoc
     */
    public function beforeSave($object)
    {
        $attributeCode = $this->getAttribute()->getName();
        if ($object->getData('use_config_' . $attributeCode)) {
            $object->setData($attributeCode, BooleanSource::VALUE_USE_CONFIG);
            return $this;
        }

        return parent::beforeSave($object);
    }
}
