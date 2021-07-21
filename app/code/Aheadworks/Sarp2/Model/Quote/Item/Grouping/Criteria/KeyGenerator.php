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
namespace Aheadworks\Sarp2\Model\Quote\Item\Grouping\Criteria;

use Aheadworks\Sarp2\Model\Quote\Item\Grouping\CriterionPool;
use Magento\Quote\Model\Quote\Item;

/**
 * Class KeyGenerator
 * @package Aheadworks\Sarp2\Model\Quote\Item\Grouping\Criteria
 */
class KeyGenerator
{
    /**
     * @var CriterionPool
     */
    private $criterionPool;

    /**
     * @var KeyFactory
     */
    private $keyFactory;

    /**
     * @param CriterionPool $criterionPool
     * @param KeyFactory $keyFactory
     */
    public function __construct(
        CriterionPool $criterionPool,
        KeyFactory $keyFactory
    ) {
        $this->criterionPool = $criterionPool;
        $this->keyFactory = $keyFactory;
    }

    /**
     * Generate key
     *
     * @param Item $item
     * @param array $criteria
     * @return Key
     */
    public function generate($item, array $criteria)
    {
        $instances = [];
        $keyParts = [];
        foreach ($criteria as $criteriaCode) {
            $criterion = $this->criterionPool->getCriterion($criteriaCode);
            if ($criterion) {
                $value = $criterion->getValue($item);
                if ($value !== null) {
                    $keyParts[] = $value;
                    $instances[] = $criterion;
                }
            }
        }
        return $this->keyFactory->create(
            [
                'value' => implode('-', $keyParts),
                'criterionInstances' => $instances
            ]
        );
    }
}
