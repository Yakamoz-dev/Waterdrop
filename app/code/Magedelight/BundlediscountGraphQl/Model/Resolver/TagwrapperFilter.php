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

use Magedelight\Bundlediscount\Api\BundleTagInterface;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Query\Resolver\Argument\SearchCriteria\Builder as SearchCriteriaBuilder;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;

class TagwrapperFilter implements ResolverInterface {
	/**
	 * @var BundleTagInterface
	 */
	private $bundleTagWrapperRepository;
	/**
	 * @var SearchCriteriaBuilder
	 */
	private $searchCriteriaBuilder;

	/**
	 * @param BundleTagInterface $bundleTagWrapperRepository
	 * @param SearchCriteriaBuilder  $searchCriteriaBuilder
	 */
	public function __construct(
		BundleTagInterface $bundleTagWrapperRepository,
		SearchCriteriaBuilder $searchCriteriaBuilder
	) {
		$this->bundleTagWrapperRepository = $bundleTagWrapperRepository;
		$this->searchCriteriaBuilder = $searchCriteriaBuilder;
	}
	/**
	 * @inheritdoc
	 */
	public function resolve(Field $field, $context, ResolveInfo $info, array $value = null, array $args = null) {
		$this->vaildateArgs($args);
		$searchCriteria = $this->searchCriteriaBuilder->build('tag_wrapper', $args);
		$searchCriteria->setCurrentPage($args['currentPage']);
		$searchCriteria->setPageSize($args['pageSize']);
		$searchResult = $this->bundleTagWrapperRepository->getList($searchCriteria);
		return [
			'total_count' => $searchResult->getTotalCount(),
			'items' => $searchResult->getItems(),
		];
	}
	/**
	 * @param array $args
	 * @throws GraphQlInputException
	 */
	private function vaildateArgs(array $args): void {
		if (isset($args['currentPage']) && $args['currentPage'] < 1) {
			throw new GraphQlInputException(__('currentPage value must be greater than 0.'));
		}
		if (isset($args['pageSize']) && $args['pageSize'] < 1) {
			throw new GraphQlInputException(__('pageSize value must be greater than 0.'));
		}
	}
}