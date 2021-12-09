<?php

/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

declare (strict_types = 1);

namespace Magedelight\BundlediscountGraphQl\Model\Resolver;

use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Exception\GraphQlNoSuchEntityException;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;

/**
 * Quote data field resolver, used for GraphQL request processing
 */
class TagCategoriesApi implements ResolverInterface {

	protected $mdTagCategoryFactory;

	public function __construct(
		\Magedelight\Bundlediscount\Model\TagcategoriesFactory $mdTagCategoryFactory
	) {
		$this->mdTagCategoryFactory = $mdTagCategoryFactory;
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
		$tagCategoriesId = $this->getTagCategoriesId($args);
		$tagCategoriesData = $this->getTagCategoriesData($tagCategoriesId);

		return $tagCategoriesData;
	}

	/**
	 * @param array $args
	 * @return int
	 * @throws GraphQlInputException
	 */
	private function getTagCategoriesId(array $args): int {
		if (!isset($args['id'])) {
			throw new GraphQlInputException(__('Tag Categories ID should be specified'));
		}

		return (int) $args['id'];
	}

	/**
	 * @param int $quoteId
	 * @return array
	 * @throws GraphQlNoSuchEntityException
	 */
	private function getTagCategoriesData(int $tagCategoriesId): array
	{
		try {
			$tagCategoryObj = $this->mdTagCategoryFactory->create()->load($tagCategoriesId);
			if (!$tagCategoryObj->getId()) {
				throw new GraphQlInputException(__($tagCategoriesId . ' Record is not exist.'));
			}
			$tagCategoryData = [
				'name' => $tagCategoryObj->getName(),
				'is_active' => $tagCategoryObj->getIsActive(),
				'created_at' => $tagCategoryObj->getCreatedAt(),
				'update_time' => $tagCategoryObj->getUpdateTime(),
			];
		} catch (NoSuchEntityException $e) {
			throw new GraphQlNoSuchEntityException(__($e->getMessage()), $e);
		}
		return $tagCategoryData;
	}
}