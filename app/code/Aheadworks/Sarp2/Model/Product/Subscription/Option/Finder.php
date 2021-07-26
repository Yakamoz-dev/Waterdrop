<?php
/**
 * Aheadworks Inc.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://aheadworks.com/end-user-license-agreement/
 *
 * @package    Sarp2
 * @version    2.15.0
 * @copyright  Copyright (c) 2021 Aheadworks Inc. (https://aheadworks.com/)
 * @license    https://aheadworks.com/end-user-license-agreement/
 */
namespace Aheadworks\Sarp2\Model\Product\Subscription\Option;

use Aheadworks\Sarp2\Api\Data\SubscriptionOptionInterface;
use Aheadworks\Sarp2\Api\SubscriptionOptionRepositoryInterface;
use Magento\Framework\Exception\LocalizedException;

/**
 * Class Finder
 * @package Aheadworks\Sarp2\Model\Product\Subscription\Option
 */
class Finder
{
    /**
     * @var SubscriptionOptionRepositoryInterface
     */
    private $optionRepository;

    /**
     * @var SortOrderResolver
     */
    private $sortOrderResolver;

    /**
     * @param SubscriptionOptionRepositoryInterface $optionRepository
     * @param SortOrderResolver $sortOrderResolver
     */
    public function __construct(
        SubscriptionOptionRepositoryInterface $optionRepository,
        SortOrderResolver $sortOrderResolver
    ) {
        $this->optionRepository = $optionRepository;
        $this->sortOrderResolver = $sortOrderResolver;
    }

    /**
     * Get sorted options
     *
     * @param int $productId
     * @return SubscriptionOptionInterface[]
     * @throws LocalizedException
     */
    public function getSortedOptions($productId)
    {
        $options = $this->optionRepository->getList($productId);
        $optionsToSort = [];
        $optionsWithDefaultOrder = [];
        foreach ($options as $option) {
            if ($this->sortOrderResolver->getSortOrder($option) === null) {
                $optionsWithDefaultOrder[] = $option;
            } else {
                $optionsToSort[] = $option;
            }
        }
        usort($optionsToSort, [$this, 'compare']);

        return array_merge($optionsToSort, $optionsWithDefaultOrder);
    }

    /**
     * Compare sort order of option A with sort order of option B
     *
     * @param $a
     * @param $b
     * @return int
     */
    public function compare($a, $b)
    {
        $sortOrderA = $this->sortOrderResolver->getSortOrder($a);
        $sortOrderB = $this->sortOrderResolver->getSortOrder($b);
        if ($sortOrderA == $sortOrderB) {
            return 0;
        }

        return ($sortOrderA > $sortOrderB) ? 1 : -1;
    }
}
