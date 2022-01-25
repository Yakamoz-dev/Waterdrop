<?php

/**
 * Magedelight
 * Copyright (C) 2016 Magedelight <info@magedelight.com>.
 *
 * NOTICE OF LICENSE
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see http://opensource.org/licenses/gpl-3.0.html.
 *
 * @category Magedelight
 *
 * @copyright Copyright (c) 2016 Mage Delight (http://www.magedelight.com/)
 * @license http://opensource.org/licenses/gpl-3.0.html GNU General Public License,version 3 (GPL-3.0)
 * @author Magedelight <info@magedelight.com>
 */

namespace Magedelight\Bundlediscount\Model;

use Magento\Catalog\Model\ProductFactory;

class Bundleitems extends \Magento\Framework\Model\AbstractModel
{
    protected $_taxHelper;

    /**
     * Bundleitems constructor.
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Catalog\Helper\Data $catalogHelper
     * @param \Magento\Tax\Helper\Data $taxHelper
     * @param ProductFactory $productFactory
     * @param \Magento\Catalog\Helper\ImageFactory $imageHelperFactory
     * @param \Magento\Framework\Model\ResourceModel\AbstractResource|null $resource
     * @param \Magento\Framework\Data\Collection\AbstractDb|null $resourceCollection
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Catalog\Helper\Data $catalogHelper,
        \Magento\Tax\Helper\Data $taxHelper,
        \Magento\Catalog\Model\ProductFactory $productFactory,
        \Magento\Catalog\Helper\ImageFactory $imageHelperFactory,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        $this->productFactory = $productFactory;
        $this->_taxHelper = $taxHelper;
        $this->_catalogData = $catalogHelper;
        $this->imageHelperFactory = $imageHelperFactory;
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    public function _construct()
    {
        $this->_init('Magedelight\Bundlediscount\Model\ResourceModel\Bundleitems');
    }

    /**
     * @param $identifier
     * @param $storeId
     * @return mixed
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function checkIdentifier($identifier, $storeId)
    {
        return $this->_getResource()->checkIdentifier($identifier, $storeId);
    }

    /**
     * @param $bundleId
     * @return \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection|void|null
     */
    public function getItemsByBundle($bundleId)
    {
        $items = null;

        $data = [];
        if ($bundleId) {
            $displayBothPrice = (boolean) $this->_taxHelper->displayBothPrices();
            $displayIncludeTaxPrice = (boolean) $this->_taxHelper->displayPriceIncludingTax();

            $items = $this->getCollection()
                    ->addFieldToFilter('bundle_id', ['eq' => $bundleId])
                    ->setOrder('sort_order', 'ASC');

            foreach ($items as $item) {
                $ass_product = $this->productFactory->create()->load($item->getProductId());
                if ($displayBothPrice) {
                    $finalPrice = $this->_catalogData->getTaxPrice(
                        $ass_product,
                        $ass_product->getFinalPrice(),
                        true,
                        null,
                        null,
                        null,
                        null,
                        null,
                        false
                    );
                } else {
                    if ($displayIncludeTaxPrice) {
                        $finalPrice = $this->_catalogData->getTaxPrice(
                            $ass_product,
                            $ass_product->getFinalPrice(),
                            true,
                            null,
                            null,
                            null,
                            null,
                            null,
                            false
                        );
                    } else {
                        $finalPrice = $this->_catalogData->getTaxPrice(
                            $ass_product,
                            $ass_product->getFinalPrice(),
                            false,
                            null,
                            null,
                            null,
                            null,
                            null,
                            false
                        );
                    }
                }
                $items->getItemByColumnValue('item_id', $item->getId())
                        ->setName($ass_product->getName())
                        ->setPrice($finalPrice)
                        ->setImageUrl($this->getImage($ass_product))
                        ->setTypeId($ass_product->getTypeId())
                        ->setHasCustomOptions($ass_product->getOptions() ? 1 : 0)
                        ->setIsSalable(($ass_product->isSalable()) ? 1 : 0)
                        ->setProductUrl($ass_product->getProductUrl())
                        ->setSku($ass_product->getSku());
            }

            return ($items->count() > 0) ? $items : null;
        } else {
            return;
        }
    }

    /**
     * @param $product
     * @return string
     */
    public function getImage($product)
    {
        $imageUrl = $this->imageHelperFactory->create()->init($product, 'category_page_list')
            ->constrainOnly(false)->keepTransparency(true)->keepAspectRatio(true)
            ->keepFrame(true)->backgroundColor([255, 255, 255])->getUrl();
        return $imageUrl;
    }

    /**
     * @return bool
     */
    public function hasOptions()
    {
        $hasOptions = false;
        $productTypes = ['grouped', 'configurable', 'bundle', 'downloadable'];

        if (in_array($this->getTypeId(), $productTypes)) {
            $hasOptions = true;
        }

        return $hasOptions;
    }

    /**
     * @return bool
     */
    public function hasCustomOptions()
    {
        $hasCustomOptions = false;

        if ($this->getHasCustomOptions() == 1) {
            $hasCustomOptions = true;
        }

        return $hasCustomOptions;
    }
}
