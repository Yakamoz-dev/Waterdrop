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

use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Exception\GraphQlNoSuchEntityException;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;

class DeleteBundleOptionsApi implements ResolverInterface {

	/**
	 * @var \Magedelight\Bundlediscount\Model\BundlediscountFactory
	 */
	protected $mdBundleDiscountObj;

	/**
	 * @var \Magedelight\Bundlediscount\Model\ResourceModel\Bundleitems\CollectionFactory
	 */
	protected $mdBundleItemsCollObj;

	/**
	 * @var \Magedelight\Bundlediscount\Model\BundleitemsFactory
	 */
	protected $mdBundleItemsObj;

	/**
	 * @param \Magedelight\Bundlediscount\Model\BundlediscountFactory                       $mdBundleDiscountObj
	 * @param \Magedelight\Bundlediscount\Model\ResourceModel\Bundleitems\CollectionFactory $mdBundleItemsCollObj
	 * @param \Magedelight\Bundlediscount\Model\BundleitemsFactory                          $mdBundleItemsObj
	 */
	public function __construct(
		\Magedelight\Bundlediscount\Model\BundlediscountFactory $mdBundleDiscountObj,
		\Magedelight\Bundlediscount\Model\ResourceModel\Bundleitems\CollectionFactory $mdBundleItemsCollObj,
		\Magedelight\Bundlediscount\Model\BundleitemsFactory $mdBundleItemsObj
	) {
		$this->mdBundleDiscountObj = $mdBundleDiscountObj;
		$this->mdBundleItemsCollObj = $mdBundleItemsCollObj;
		$this->mdBundleItemsObj = $mdBundleItemsObj;
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
		if (!isset($args['bundle_id'])) {
			throw new GraphQlInputException(__('Specify the Bundle Options Id.'));
		}
		$mdBundleDiscount = $this->mdBundleDiscountObj->create()->load($args['bundle_id']);
		if (!$mdBundleDiscount->getId()) {
			throw new GraphQlNoSuchEntityException(
				__('Could not find a bundle options : %1', $args['bundle_id'])
			);
		}

		$mdBundleDiscountItem = $this->mdBundleItemsCollObj->create()->addFieldToSelect('*')->addFieldToFilter('bundle_id', ['eq' => $args['bundle_id']]);
		if ($mdBundleDiscountItem->getSize() > 0) {
			foreach ($mdBundleDiscountItem as $key => $value) {
				$mdBundleOptObj = $this->mdBundleItemsObj->create()->load($value->getId());
				$mdBundleOptObj->delete();
			}
		}

		return ['result' => $mdBundleDiscount->delete()];
	}
}