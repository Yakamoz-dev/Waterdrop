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

class CreateBundleOptionsApi implements ResolverInterface {

	/**
	 * @var \Magedelight\Bundlediscount\Model\BundlediscountFactory
	 */
	protected $mdBundleDiscountObj;

	/**
	 * @var \Magedelight\Bundlediscount\Model\BundleitemsFactory
	 */
	protected $mdBundleItemsObj;

	/**
	 * @var \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory
	 */
	protected $productFactory;

	/**
	 * @var \Magento\Customer\Model\ResourceModel\Group\CollectionFactory
	 */
	protected $groupCollFactory;

	/**
	 * @var \Magedelight\Bundlediscount\Model\TagwrapperFactory
	 */
	protected $mdTagWrapperFactory;

	/**
	 * @var \Magento\Store\Model\StoreRepository
	 */
	protected $storeRepo;

	/**
	 * @param \Magedelight\Bundlediscount\Model\BundlediscountFactory        $mdBundleDiscountObj
	 * @param \Magedelight\Bundlediscount\Model\BundleitemsFactory           $mdBundleItemsObj
	 * @param \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productFactory
	 * @param \Magedelight\Bundlediscount\Model\TagWrapperFactory            $mdTagWrapperFactory
	 * @param \Magento\Customer\Model\ResourceModel\Group\CollectionFactory  $groupCollFactory
	 * @param \Magento\Store\Model\StoreRepository                           $storeRepo
	 */
	public function __construct(
		\Magedelight\Bundlediscount\Model\BundlediscountFactory $mdBundleDiscountObj,
		\Magedelight\Bundlediscount\Model\BundleitemsFactory $mdBundleItemsObj,
		\Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productFactory,
		\Magento\Customer\Model\ResourceModel\Group\CollectionFactory $groupCollFactory,
		\Magedelight\Bundlediscount\Model\ResourceModel\Tagwrapper\CollectionFactory $mdTagWrapperFactory,
		\Magento\Store\Model\StoreRepository $storeRepo
	) {
		$this->mdBundleDiscountObj = $mdBundleDiscountObj;
		$this->mdBundleItemsObj = $mdBundleItemsObj;
		$this->productFactory = $productFactory;
		$this->groupCollFactory = $groupCollFactory;
		$this->mdTagWrapperFactory = $mdTagWrapperFactory;
		$this->storeRepo = $storeRepo;
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
		if (empty($args['input']) || !is_array($args['input'])) {
			throw new GraphQlInputException(__('"input" value should be specified'));
		}
		$bundleOptionsData = $this->createBundleOptions($args['input']);
		return ['bundle_options' => $bundleOptionsData];
	}

	/**
	 * @param array $args
	 * @return array
	 * @throws GraphQlInputException
	 */
	private function createBundleOptions(array $args): array{
		try {
			$status = '';
			if ($args['status'] == 1) {
				$status = "Enabled";
			}
			if ($args['status'] == 2) {
				$status = "Disabled";
			}
			if ($args['status'] != 1 || $args['status'] != 2) {
				throw new GraphQlInputException(__('Please enter value of status from 1 or 2. 1 is for Enabled and 2 is for Disabled'));
			}
			$tagCategoryDataArray = [];
			$bundleOptions = $this->mdBundleDiscountObj->create();
			$discount_type = '';
			$exclude_base_product = '';
			$bundle_tags = '';
			if ($args['discount_type'] == "Fixed") {
				$discount_type = 0;
			} elseif ($args['discount_type'] == "Percentage") {
				$discount_type = 1;
			} else {
				throw new GraphQlInputException(__($args['discount_type'] . ' discount type is invalid.'));
			}

			if ($args['exclude_base_product'] == "Yes" || $args['exclude_base_product'] == 1) {
				$exclude_base_product = 1;
			} elseif ($args['exclude_base_product'] == "No" || $args['exclude_base_product'] == 0) {
				$exclude_base_product = 0;
			} else {
				throw new GraphQlInputException(__($args['exclude_base_product'] . ' Ignore Base Product From Discount is invalid.'));
			}

			$tagWrapperObj = $this->mdTagWrapperFactory->create()->addFieldToSelect('*')->addFieldToFilter('name', ['in' => explode(",", $args['bundle_tags'])]);

			$bundle_tags_arr = [];
			foreach ($tagWrapperObj as $key => $value) {
				$bundle_tags_arr[] = $value->getId();
			}

			if (count($bundle_tags_arr) > 0) {
				$bundle_tags = implode(",", $bundle_tags_arr);
			} else {
				throw new GraphQlInputException(__($args['bundle_tags'] . ' is invalid.'));
			}
			$custGrpArr = explode(",", $args['customer_groups']);

			$groupColl = $this->groupCollFactory->create()->loadData()->toOptionArray();
			$grpCode = [];
			foreach ($custGrpArr as $grpKey => $grpValue) {
				foreach ($groupColl as $key => $value) {
					if (trim($grpValue) == $value['label']) {
						$grpCode[] = $value['value'];
						break;
					}
				}
			}

			$storeList = $this->getStoreList();
			$storeArr = [];
			$storeIds = explode(",", $args['store_ids']);
			foreach ($storeIds as $storeKey => $storeValue) {
				$logger->info("--" . $storeValue);
				foreach ($storeList as $key => $value) {
					$logger->info("*-" . $value);
					if (trim($storeValue) == $value) {
						$storeArr[] = $key;
						break;
					}
				}
			}

			$productColl = $this->productFactory->create()->addFieldToSelect('*')->addFieldToFilter('name', ['eq' => $args['product_name']])->getLastItem();

			$bundleOptionsData = [
				'name' => $args['name'],
				'product_name' => $productColl->getName(),
				'is_active' => $args['status'],
				'qty' => $args['qty'],
				'date_from' => $args['date_from'],
				'date_to' => $args['date_to'],
				'bundle_option' => $args['bundle_option'],
				'discount_type' => $discount_type,
				'discount_price' => $args['discount_price'],
				'exclude_base_product' => $exclude_base_product,
				'bundle_keywords' => $args['bundle_keywords'],
				'bundle_tags' => $bundle_tags,
				'sort_order' => $args['sort_order'],
				'customer_groups' => implode(",", $grpCode),
				'store_ids' => implode(",", $storeArr),

			];

			$bundleOptions->setData($bundleOptionsData);
			$bundleOptions->save();
			$bundleDiscObjData = [
				'bundle_id' => $bundleOptions->getId(),
				'name' => $bundleOptions->getName(),
				'is_active' => $bundleOptions->getIsActive(),
				'qty' => $bundleOptions->getQty(),
				'date_from' => $bundleOptions->getDateFrom(),
				'date_to' => $bundleOptions->getDateTo(),
				'bundle_option' => $bundleOptions->getBundleOption(),
				'discount_type' => $bundleOptions->getDiscountType(),
				'discount_price' => $bundleOptions->getDiscountPrice(),
				'exclude_base_product' => $bundleOptions->getExcludeBaseProduct(),
				'bundle_keywords' => $bundleOptions->getBundleKeywords(),
				'bundle_tags' => $bundleOptions->getBundleTags(),
				'sort_order' => $bundleOptions->getSortOrder(),
				'customer_groups' => $bundleOptions->getCustomerGroups(),
				'store_ids' => $bundleOptions->getStoreIds(),
			];
			if (isset($args['products_collection'])) {
				foreach ($args['products_collection'] as $key => $value) {
					if ($value['name'] != $productColl->getName()) {
						$mdBundleItemsObj = $this->mdBundleItemsObj->create();
						$productColl = $this->productFactory->create()->addFieldToSelect('*')->addFieldToFilter('name', ['eq' => $value['name']])->getLastItem();
						$mdBundleItemsObj->setData('bundle_id', $bundleOptions->getId());
						if (!$productColl->getId()) {
							throw new GraphQlInputException(__($value['name'] . ' product name is invalid'));
						}
						$mdBundleItemsObj->setData('product_id', $productColl->getId());
						$mdBundleItemsObj->setData('qty', $value['qty']);
						$mdBundleItemsObj->setData('sort_order', $value['sort_order']);
						$mdBundleItemsObj->save();
						$bundleDiscObjData['products_collection'][$key]['bundle_id'] = $bundleOptions->getId();
						$bundleDiscObjData['products_collection'][$key]['name'] = $productColl->getName();
						$bundleDiscObjData['products_collection'][$key]['qty'] = $value['qty'];
						$bundleDiscObjData['products_collection'][$key]['sort_order'] = $value['sort_order'];
					} else {
						throw new GraphQlInputException(__($value['name'] . ' same product name is invalid'));
					}
				}
			}
		} catch (NoSuchEntityException $e) {
			throw new GraphQlNoSuchEntityException(__($e->getMessage()), $e);
		}
		return $tagCategoryDataArray;
	}

	private function getStoreList() {
		$storeListColl = $this->storeRepo->getList();
		$websiteIds = [];
		$storeList = [];
		foreach ($storeListColl as $store) {
			$websiteId = $store["website_id"];
			$storeId = $store["store_id"];
			$storeName = $store["name"];
			$storeList[$storeId] = $storeName;
			array_push($websiteIds, $websiteId);
		}
		return $storeList;
	}
}