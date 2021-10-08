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
namespace Aheadworks\Sarp2\Model\Product\Attribute\Source;

use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;
use Magento\Framework\Data\Collection as DataCollection;

/**
 * Class SubscriptionType
 * @package Aheadworks\Sarp2\Model\Product\Attribute\Source
 */
class SubscriptionType extends AbstractSource
{
    /**
     * 'No' options
     */
    const NO = 1;

    /**
     * 'Optional' option
     */
    const OPTIONAL = 2;

    /**
     * 'Subscription only' option
     */
    const SUBSCRIPTION_ONLY = 3;

    /**
     * {@inheritdoc}
     */
    public function getAllOptions()
    {
        if (!$this->_options) {
            $this->_options = [
                ['value' => self::NO, 'label' => __('No')],
                ['value' => self::OPTIONAL, 'label' => __('Optional')],
                ['value' => self::SUBSCRIPTION_ONLY, 'label' => __('Subscription only')]
            ];
        }
        return $this->_options;
    }

    /**
     * {@inheritdoc}
     */
    public function addValueSortToCollection($collection, $dir = DataCollection::SORT_ORDER_DESC)
    {
        $attributeCode = $this->getAttribute()->getAttributeCode();
        $attributeId = $this->getAttribute()->getId();
        $attributeTable = $this->getAttribute()->getBackend()->getTable();
        $linkField = $this->getAttribute()->getEntity()->getLinkField();

        $defaultValueTable = $attributeCode . '_default';
        $storeValueTable = $attributeCode . '_store';
        $collection->getSelect()
            ->joinLeft(
                [$defaultValueTable => $attributeTable],
                'e.' . $linkField . '=' . $defaultValueTable . '.' . $linkField .
                ' AND ' . $defaultValueTable . '.attribute_id=\'' . $attributeId . '\''.
                ' AND ' . $defaultValueTable . '.store_id=\'0\'',
                []
            )
            ->joinLeft(
                [$storeValueTable => $attributeTable],
                'e.' . $linkField . '=' . $storeValueTable . '.' . $linkField .
                ' AND ' . $storeValueTable . '.attribute_id=\'' . $attributeId . '\'' .
                ' AND ' . $storeValueTable . '.store_id=\'' . $collection->getStoreId() . '\'',
                []
            );
        $valueExpr = $collection->getConnection()
            ->getCheckSql(
                $storeValueTable . '.value_id > 0',
                $storeValueTable . '.value',
                $defaultValueTable . '.value'
            );

        $collection->getSelect()->order($valueExpr . ' ' . $dir);

        return $this;
    }
}
