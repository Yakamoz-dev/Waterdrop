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
namespace Aheadworks\Sarp2\Model\Sales\Total\Profile\Total\Group;

use Aheadworks\Sarp2\Api\Data\PlanDefinitionInterface;
use Aheadworks\Sarp2\Api\Data\ProfileItemInterface;
use Magento\Bundle\Model\Product\Type as BundleType;
use Magento\Quote\Api\Data\CartItemInterface;

/**
 * Class Initial
 *
 * @package Aheadworks\Sarp2\Model\Sales\Total\Profile\Total\Group
 */
class Initial extends AbstractProfileGroup
{
    /**
     * {@inheritdoc}
     */
    public function getCode()
    {
        return self::CODE_INITIAL;
    }

    /**
     * {@inheritdoc}
     */
    public function getItemPrice($item, $useBaseCurrency)
    {
        $result = 0.0;
        $option = $this->getItemOption($item);
        if ($option) {
            $planDefinition = $option->getPlan()->getDefinition();

            $initialFee = $option->getInitialFee();
            $baseItemPrice = $initialFee != 0 && $planDefinition->getIsInitialFeeEnabled()
                ? (float)$initialFee
                : 0;

            $baseItemPrice += $this->getPeriodPrice($item, $planDefinition);

            $result = $useBaseCurrency
                ? $baseItemPrice
                : $this->priceCurrency->convert($baseItemPrice);

            if ($this->isBundleChild($item)) {
                $childrenCount = count($item->getParentItem()->getChildItems());
                $result /= $childrenCount;
                $result /= $item->getQty();
            }
        } else {
            $result = $item->getRegularPrice();
        }

        return $result;
    }

    /**
     * Check is bundle child item
     *
     * @param CartItemInterface|ProfileItemInterface $item
     * @return bool
     */
    private function isBundleChild($item)
    {
        return $item->getParentItem() && $item->getParentItem()->getProductType() == BundleType::TYPE_CODE;
    }

    /**
     * @param CartItemInterface|ProfileItemInterface $item
     * @param PlanDefinitionInterface $planDefinition
     * @return float
     */
    private function getPeriodPrice($item, $planDefinition)
    {
        $item = $item->getParentItem() ?: $item;

        return $planDefinition->getIsTrialPeriodEnabled()
            ? $item->getTrialPrice()
            : $item->getRegularPrice();
    }
}
