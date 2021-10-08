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
 * @version    2.15.3
 * @copyright  Copyright (c) 2021 Aheadworks Inc. (https://aheadworks.com/)
 * @license    https://aheadworks.com/end-user-license-agreement/
 */
namespace Aheadworks\Sarp2\Model\Quote\Item\Grouping\Criteria;

use Aheadworks\Sarp2\Model\Quote\Item\Grouping\CriterionInterface;

/**
 * Class Key
 * @package Aheadworks\Sarp2\Model\Quote\Item\Grouping\Criteria
 */
class Key
{
    /**
     * @var string
     */
    private $value;

    /**
     * @var CriterionInterface[]
     */
    private $criterionInstances = [];

    /**
     * @param $value
     * @param array $criterionInstances
     */
    public function __construct(
        $value,
        array $criterionInstances
    ) {
        $this->value = $value;
        $this->criterionInstances = $criterionInstances;
    }

    /**
     * Get key value
     *
     * @return string|null
     */
    public function getValue()
    {
        return !empty($this->value) ? $this->value : null;
    }

    /**
     * Get criteria instances
     *
     * @return CriterionInterface[]
     */
    public function getCriteriaInstances()
    {
        return array_values($this->criterionInstances);
    }
}
