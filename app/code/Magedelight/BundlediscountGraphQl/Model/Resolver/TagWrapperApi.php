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

class TagWrapperApi implements ResolverInterface {

	protected $mdTagWrapperFactory;

	public function __construct(
		\Magedelight\Bundlediscount\Model\TagwrapperFactory $mdTagWrapperFactory
	) {
		$this->mdTagWrapperFactory = $mdTagWrapperFactory;
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
		$tagWrapperId = $this->getTagWrapperId($args);
		$tagWrapperData = $this->getTagWrapperData($tagWrapperId);

		return $tagWrapperData;
	}

	/**
	 * @param array $args
	 * @return int
	 * @throws GraphQlInputException
	 */
	private function getTagWrapperId(array $args): int {
		if (!isset($args['id'])) {
			throw new GraphQlInputException(__('Tag ID should be specified'));
		}

		return (int) $args['id'];
	}

	/**
	 * @param int $quoteId
	 * @return array
	 * @throws GraphQlNoSuchEntityException
	 */
	private function getTagWrapperData(int $tagWrapperId): array
	{
		try {
			/* Fixed Notice: Undefined variable: args Fixed in Magento 2.3.5 */
			/*if (trim($args['name']) == "") {
				throw new GraphQlInputException(__('Please enter name value'));
			}
			*/
			$tagWrapperObj = $this->mdTagWrapperFactory->create()->load($tagWrapperId);
			if (!$tagWrapperObj->getId()) {
				throw new GraphQlInputException(__($tagWrapperId . ' Record is not exist.'));
			}
			$tagWrapperData = [
				'category' => $tagWrapperObj->getCategory(),
				'name' => $tagWrapperObj->getName(),
				'is_active' => $tagWrapperObj->getIsActive(),
				'created_at' => $tagWrapperObj->getCreatedAt(),
				'update_time' => $tagWrapperObj->getUpdateTime(),
			];
		} catch (NoSuchEntityException $e) {
			throw new GraphQlNoSuchEntityException(__($e->getMessage()), $e);
		}
		return $tagWrapperData;
	}
}