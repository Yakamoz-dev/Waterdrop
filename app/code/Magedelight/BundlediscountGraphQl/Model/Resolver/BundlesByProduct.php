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

class BundlesByProduct implements ResolverInterface {

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
		$productId = $this->getProductId($args);
		$bundleOptionsData = $this->getBundlesByPro($productId);

		return $bundleOptionsData;
	}

	/**
	 * @param array $args
	 * @return int
	 * @throws GraphQlInputException
	 */
	private function getProductId(array $args): int {
		if (!isset($args['product_id'])) {
			throw new GraphQlInputException(__('Product ID should be specified'));
		}

		return (int) $args['product_id'];
	}

	/**
	 * @param int $productId
	 * @return array
	 * @throws GraphQlNoSuchEntityException
	 */
	private function getBundlesByPro(int $productId): array
	{
		try {
			$bundleDiscObj = $this->mdBundleDiscountObj->create()->addFieldToSelect('*')->addFieldToFilter('product_id', ['eq' => $productId]);
			$product = $this->productFactory->create();
			if ($bundleDiscObj->getSize() > 0) {
				foreach ($bundleDiscObj as $bundleKey => $bundleValue) {
					$bundleItemObj = $this->mdBundleItemsObj->create()->addFieldToSelect('*')->addFieldToFilter('bundle_id', ['eq' => $bundleValue->getId()]);
					$bundleDiscObjData['bundle_id'] = $bundleValue->getId();
					$bundleDiscObjData['name'] = $bundleValue->getName();
					$bundleDiscObjData['discount_price'] = $bundleValue->getDiscountPrice();
					$bundleDiscObjData['status'] = $bundleValue->getStatus();

					if ($bundleItemObj->getSize() > 0) {
						foreach ($bundleItemObj as $key => $value) {
							$productObj = $product->load($value->getProductId());
							$bundleDiscObjData['product_items'][$key]['bundle_id'] = $value->getBundleId();
							$bundleDiscObjData['product_items'][$key]['name'] = $productObj->getName();
							$bundleDiscObjData['product_items'][$key]['qty'] = $value->getQty();
							$bundleDiscObjData['product_items'][$key]['sort_order'] = $value->getSortOrder();
						}
					}
				}
			}

		} catch (NoSuchEntityException $e) {
			throw new GraphQlNoSuchEntityException(__($e->getMessage()), $e);
		}
		return $bundleDiscObjData;
	}
}