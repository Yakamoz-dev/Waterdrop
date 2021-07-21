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
namespace Aheadworks\Sarp2\Model\SalesRule\Rule\Condition\Product;

use Magento\SalesRule\Model\Rule\Condition\Product\Combine as SalesRuleProductConditionCombine;
use Magento\Rule\Model\Condition\Context as RuleConditionContext;
use Magento\SalesRule\Model\Rule\Condition\Product as SalesRuleProductCondition;
use Aheadworks\Sarp2\Model\SalesRule\Rule\Condition\Product as Sarp2SalesRuleProductCondition;

/**
 * Class Combine
 *
 * @package Aheadworks\Sarp2\Model\SalesRule\Rule\Condition\Product
 */
class Combine extends SalesRuleProductConditionCombine
{
    /**
     * @var Sarp2SalesRuleProductCondition
     */
    private $sarp2SalesRuleProductCondition;

    /**
     * @param RuleConditionContext $context
     * @param SalesRuleProductCondition $ruleConditionProduct
     * @param Sarp2SalesRuleProductCondition $sarp2SalesRuleProductCondition
     * @param array $data
     */
    public function __construct(
        RuleConditionContext $context,
        SalesRuleProductCondition $ruleConditionProduct,
        Sarp2SalesRuleProductCondition $sarp2SalesRuleProductCondition,
        array $data = []
    ) {
        parent::__construct($context, $ruleConditionProduct, $data);
        $this->sarp2SalesRuleProductCondition = $sarp2SalesRuleProductCondition;
        $this->setType(self::class);
    }

    /**
     * @inheritDoc
     */
    public function getNewChildSelectOptions()
    {
        $conditionList = parent::getNewChildSelectOptions();

        $conditionList = $this->modifyConditionsCombinationValue($conditionList);
        $conditionList = $this->addAwSarp2Conditions($conditionList);

        return $conditionList;
    }

    /**
     * Modify value class for conditions combination item
     *
     * @param array $conditionList
     * @return array
     */
    protected function modifyConditionsCombinationValue($conditionList)
    {
        foreach ($conditionList as &$conditionItem) {
            if (isset($conditionItem['value'])
                && $conditionItem['value'] === SalesRuleProductConditionCombine::class
            ) {
                $conditionItem['value'] = $this->getType();
            }
        }
        return $conditionList;
    }

    /**
     * Add AW SARP2 conditions to the condition list
     *
     * @param array $conditionList
     * @return array
     */
    protected function addAwSarp2Conditions($conditionList)
    {
        $sarp2SpecialAttributeList = $this->sarp2SalesRuleProductCondition
            ->loadAttributeOptions()
            ->getAttributeOption()
        ;
        $preparedSarp2AttributeList = [];
        foreach ($sarp2SpecialAttributeList as $attributeCode => $attributeLabel) {
            $preparedSarp2AttributeList[] = [
                'value' => Sarp2SalesRuleProductCondition::class . '|' . $attributeCode,
                'label' => $attributeLabel,
            ];
        }
        $conditionList = array_merge_recursive(
            $conditionList,
            [
                ['label' => __('AW SARP2 Attribute'), 'value' => $preparedSarp2AttributeList],
            ]
        );
        return $conditionList;
    }
}
