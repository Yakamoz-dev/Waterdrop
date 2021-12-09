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

use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Exception\GraphQlNoSuchEntityException;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;

class BundleOptionsApi implements ResolverInterface {

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
	public function resolve(
		Field $field,
		$context,
		ResolveInfo $info,
		array $value = null,
		array $args = null
	) {
		$bundleOptionsId = $this->getBundleOptionsId($args);
		$bundleOptionsData = $this->getBundleOptionsData($bundleOptionsId);

		return $bundleOptionsData;
	}

	/**
	 * @param array $args
	 * @return int
	 * @throws GraphQlInputException
	 */
	private function getBundleOptionsId(array $args): int {
		if (!isset($args['bundle_id'])) {
			throw new GraphQlInputException(__('Bundle ID should be specified'));
		}

		return (int) $args['bundle_id'];
	}

	/**
	 * @param int $quoteId
	 * @return array
	 * @throws GraphQlNoSuchEntityException
	 */
	private function getBundleOptionsData(int $bundleOptionsId): array
	{
		try {
			$bundleDiscObj = $this->mdBundleDiscountObj->create()->addFieldToSelect('*')->addFieldToFilter('bundle_id', ['eq' => $bundleOptionsId])->getLastItem();
			$bundleItemObj = $this->mdBundleItemsObj->create()->addFieldToSelect('*')->addFieldToFilter('bundle_id', ['eq' => $bundleDiscObj->getId()]);
			$productsArr = [];
			$product = $this->productFactory->create();
			if (!$bundleDiscObj->getId()) {
				throw new GraphQlInputException(__($bundleOptionsId . ' Record is not exist.'));
			}
			$bundleDiscObjData = [
				'name' => $bundleDiscObj->getName(),
				'status' => $bundleDiscObj->getStatus(),
				'qty' => $bundleDiscObj->getQty(),
				'date_from' => $bundleDiscObj->getDateFrom(),
				'date_to' => $bundleDiscObj->getDateTo(),
				'bundle_option' => $bundleDiscObj->getBundleOption(),
				'discount_type' => $bundleDiscObj->getDiscountType(),
				'discount_price' => $bundleDiscObj->getDiscountPrice(),
				'exclude_base_product' => $bundleDiscObj->getExcludeBaseProduct(),
				'bundle_keywords' => $bundleDiscObj->getBundleKeywords(),
				'bundle_tags' => $bundleDiscObj->getBundleTags(),
				'sort_order' => $bundleDiscObj->getSortOrder(),
				'customer_groups' => $bundleDiscObj->getCustomerGroups(),
				'store_ids' => $bundleDiscObj->getStoreIds(),
			];
			if ($bundleItemObj->getSize() > 0) {
				foreach ($bundleItemObj as $key => $value) {
					$productObj = $product->load($value->getProductId());
					$bundleDiscObjData['products_items'][$key]['bundle_id'] = $value->getBundleId();
					$bundleDiscObjData['products_items'][$key]['name'] = $productObj->getName();
					$bundleDiscObjData['products_items'][$key]['qty'] = $value->getQty();
					$bundleDiscObjData['products_items'][$key]['sort_order'] = $value->getSortOrder();
				}
			}

		} catch (NoSuchEntityException $e) {
			throw new GraphQlNoSuchEntityException(__($e->getMessage()), $e);
		}
		return $bundleDiscObjData;
	}
}