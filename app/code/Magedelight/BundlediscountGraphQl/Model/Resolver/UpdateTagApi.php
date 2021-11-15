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

class UpdateTagApi implements ResolverInterface {

	/**
	 * @var \Magedelight\Bundlediscount\Model\TagcategoriesFactory
	 */
	protected $mdTagWrapperFactory;

	/**
	 * @var \Magedelight\Bundlediscount\Model\ResourceModel\Tagcategories\CollectionFactory
	 */
	protected $mdTagCategoryFactory;

	/**
	 * @var \Magento\Framework\Stdlib\DateTime\TimezoneInterface
	 */
	protected $date;

	/**
	 * @param \Magedelight\Bundlediscount\Model\TagwrapperFactory                             $mdTagWrapperFactory
	 * @param \Magedelight\Bundlediscount\Model\ResourceModel\Tagcategories\CollectionFactory $mdTagCategoryFactory
	 * @param \Magento\Framework\Stdlib\DateTime\TimezoneInterface                            $date
	 */
	public function __construct(
		\Magedelight\Bundlediscount\Model\TagwrapperFactory $mdTagWrapperFactory,
		\Magedelight\Bundlediscount\Model\ResourceModel\Tagcategories\CollectionFactory $mdTagCategoryFactory,
		\Magento\Framework\Stdlib\DateTime\TimezoneInterface $date
	) {
		$this->mdTagWrapperFactory = $mdTagWrapperFactory;
		$this->mdTagCategoryFactory = $mdTagCategoryFactory;
		$this->date = $date;
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
		$tagWrapperData = $this->updateTag($args['input']);
		return ['tag_list' => $tagWrapperData];
	}

	/**
	 * @param array $args
	 * @return array
	 * @throws GraphQlInputException
	 */
	private function updateTag(array $args): array{
		try {
			if (($args['is_active'] != 1) && ($args['is_active'] != 2)) {
				throw new GraphQlInputException(__('Please enter value of status from 1 or 2. 1 is for Enabled and 2 is for Disabled'));
			}
			if (trim($args['name']) == "") {
				throw new GraphQlInputException(__('Please enter value of name field'));
			}
			$tagWrapperObj = $this->mdTagWrapperFactory->create()->load($args['id']);
			$id = $tagWrapperObj->getId();
			$name = $tagWrapperObj->getName();

			if (!$id) {
				throw new GraphQlInputException(__('Tag ID is not valid'));
			}
			if (!$name) {
				throw new GraphQlInputException(__($name . ' Tag is not available'));
			}
			$tagCategoryObj = $this->mdTagCategoryFactory->create()
				->addFieldToSelect('*')
				->addFieldToFilter('name', ['eq' => $args['category']])
				->getLastItem();
			if (!$tagCategoryObj->getId()) {
				throw new GraphQlInputException(__($args['category'] . ' Tag Category is not available.'));
			}
			$tagWrapperObj->setCategory($tagCategoryObj->getId());
			$tagWrapperObj->setName($args['name']);
			$tagWrapperObj->setIsActive($args['is_active']);
			$tagWrapperObj->save();

			$date = $this->date->date()->format('Y-m-d H:i:s');

			$tagWrapperArray = [
				'id' => $tagWrapperObj->getId(),
				'category' => $tagCategoryObj->getId(),
				'name' => $tagWrapperObj->getName(),
				'is_active' => $tagWrapperObj->getIsActive(),
				'created_at' => $tagWrapperObj->getCreatedAt(),
				'update_time' => $date,
			];

		} catch (NoSuchEntityException $e) {
			throw new GraphQlNoSuchEntityException(__($e->getMessage()), $e);
		}
		return $tagWrapperArray;
	}
}