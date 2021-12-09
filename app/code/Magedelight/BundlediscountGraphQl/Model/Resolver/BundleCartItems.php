<?php

/**
 * Magedelight
 * Copyright (C) 2019 Magedelight <info@magedelight.com>
 *
 * @category Magedelight
 * @package Magedelight_BundlediscountGraphQl
 * @copyright Copyright (c) 2019 Mage Delight (http://www.magedelight.com/)
 * @license http://opensource.org/licenses/gpl-3.0.html GNU General Public License,version 3 (GPL-3.0)
 * @author Magedelight <info@magedelight.com>
 */

declare (strict_types = 1);

namespace Magedelight\BundlediscountGraphQl\Model\Resolver;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;

/**
 * @inheritdoc
 */
class BundleCartItems implements ResolverInterface {

	/**
	 * @var \Magedelight\Bundlediscount\Model\ResourceModel\Bundlediscount\CollectionFactory
	 */
	protected $mdBundleDiscountObj;

	/**
	 * @var \Magedelight\Bundlediscount\Model\ResourceModel\Bundleitems\CollectionFactory
	 */
	protected $mdBundleItemsObj;

	/**
	 * @var \Magento\Catalog\Model\ProductFactory
	 */
	protected $productFactory;

	/**
	 * @param \Magedelight\Bundlediscount\Model\ResourceModel\Bundlediscount\CollectionFactory $mdBundleDiscountObj
	 * @param \Magedelight\Bundlediscount\Model\ResourceModel\Bundleitems\CollectionFactory    $mdBundleItemsObj
	 * @param \Magento\Catalog\Model\ProductFactory                                            $productFactory
	 */
	public function __construct(
		\Magedelight\Bundlediscount\Model\ResourceModel\Bundlediscount\CollectionFactory $mdBundleDiscountObj,
		\Magedelight\Bundlediscount\Model\ResourceModel\Bundleitems\CollectionFactory $mdBundleItemsObj,
		\Magento\Catalog\Model\ProductFactory $productFactory
	) {
		$this->mdBundleDiscountObj = $mdBundleDiscountObj;
		$this->mdBundleItemsObj = $mdBundleItemsObj;
		$this->productFactory = $productFactory;
	}

	/**
	 * @inheritdoc
	 */
	public function resolve(Field $field, $context, ResolveInfo $info, array $value = null, array $args = null) {
		if (!isset($value['model'])) {
			throw new LocalizedException(__('"model" value should be specified'));
		}
		$cart = $value['model'];

		$itemsData = [];

		$bundleIds = explode(",", $cart->getBundleIds());
		$proIds = [];
		if (count($bundleIds) > 0) {
			foreach ($bundleIds as $key => $value) {
				$bundleObj = $this->mdBundleDiscountObj->create()
					->addFieldToSelect('*')
					->addFieldToFilter('bundle_id', ['eq' => $value])
					->getLastItem();
				if (!in_array($bundleObj->getProductId(), $proIds)) {
					$proIds[] = $bundleObj->getProductId();
				}
			}
		}
		if (count($proIds) > 0) {
			$bundleProObj = $this->mdBundleDiscountObj->create()
				->addFieldToSelect('*')
				->addFieldToFilter('product_id', ['in' => $proIds]);
			$bundleIds = [];
			foreach ($bundleProObj as $key => $value) {
				$bundleIds[] = $value->getId();
			}
			$bundleItemsObj = $this->mdBundleItemsObj->create()
				->addFieldToSelect('*')
				->addFieldToFilter('bundle_id', ['in' => $bundleIds]);
			foreach ($bundleItemsObj as $key => $value) {
				$product = $this->productFactory->create()->load($value->getProductId());
				$itemsData[] = [
					'bundle_id' => $value->getBundleId(),
					'product_id' => $product->getId(),
					'name' => $product->getName(),
					'sku' => $product->getSku(),
					'qty' => $value->getQty(),
				];

			}
		}
		return $itemsData;
	}
}
