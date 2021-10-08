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
namespace Aheadworks\Sarp2\Model\ResourceModel\Product;

use Magento\Catalog\Model\ResourceModel\AbstractResource;
use Magento\Catalog\Model\Product;

/**
 * Class EntityIdResolver
 *
 * @package Aheadworks\Sarp2\Model\ResourceModel\Product
 */
class EntityIdResolver extends AbstractResource
{
    /**
     * Initialize connection
     *
     * @return void
     */
    protected function _construct()
    {
        $resource = $this->_resource;
        $this
            ->setType(Product::ENTITY)
            ->setConnection($resource->getConnection('catalog'));
    }

    /**
     * Resolve entity ID
     *
     * @param int $entityId
     * @return int
     */
    public function resolve($entityId)
    {
        if ($this->getIdFieldName() == $this->getLinkField()) {
            return $entityId;
        }
        $select = $this->getConnection()->select();
        $tableName = $this->_resource->getTableName('catalog_product_entity');
        $select->from($tableName, [$this->getLinkField()])
            ->where('entity_id = ?', $entityId);
        return $this->getConnection()->fetchOne($select);
    }
}
