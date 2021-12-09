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

class DeleteTagCategoriesApi implements ResolverInterface {
	/**
	 * @var \Magedelight\Bundlediscount\Model\TagcategoriesFactory
	 */
	private $mdTagCategoryFactory;

	/**
	 * @param \Magedelight\Bundlediscount\Model\TagcategoriesFactory $mdTagCategoryFactory
	 */
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
		if (!isset($args['id'])) {
			throw new GraphQlInputException(__('Specify the Category Tag Id.'));
		}

		$tagCategoryObj = $this->mdTagCategoryFactory->create()->load($args['id']);
		if (!$tagCategoryObj->getId()) {
			throw new GraphQlNoSuchEntityException(
				__('Could not find a tag category : %1', $args['id'])
			);
		}

		return ['result' => $tagCategoryObj->delete()];
	}
}
