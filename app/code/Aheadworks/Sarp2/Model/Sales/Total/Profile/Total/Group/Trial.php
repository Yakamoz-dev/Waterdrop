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
namespace Aheadworks\Sarp2\Model\Sales\Total\Profile\Total\Group;

use Aheadworks\Sarp2\Model\Plan\Resolver\ByPeriod\StrategyPool;

/**
 * Class Trial
 *
 * @package Aheadworks\Sarp2\Model\Sales\Total\Profile\Total\Group
 */
class Trial extends AbstractProfileGroup
{
    /**
     * {@inheritdoc}
     */
    public function getCode()
    {
        return self::CODE_TRIAL;
    }

    /**
     * {@inheritdoc}
     */
    public function getItemPrice($item, $useBaseCurrency)
    {
        $result = 0.0;
        $option = $this->getItemOption($item);
        if ($option) {
            $plan = $option->getPlan();

            if ($plan->getDefinition()->getIsTrialPeriodEnabled()) {
                $product = $this->getProduct($item);
                $baseItemPrice = $this->priceCalculation->getTrialPrice(
                    $product->getEntityId(),
                    $item->getQty(),
                    $option
                );
                $result = $useBaseCurrency
                    ? $baseItemPrice
                    : $this->priceCurrency->convert($baseItemPrice);

                $result = $this->customOptionCalculator->applyOptionsPrice($item, $result, $useBaseCurrency, true);
                $result = $this->bundleOptionCalculator->applyBundlePrice(
                    $item,
                    $result,
                    $option->getPlanId(),
                    $useBaseCurrency,
                    StrategyPool::TYPE_TRIAL
                );
            }
        } else {
            $result = $item->getRegularPrice();
        }

        return $result;
    }
}
