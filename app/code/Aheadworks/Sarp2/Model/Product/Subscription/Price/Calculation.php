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
namespace Aheadworks\Sarp2\Model\Product\Subscription\Price;

use Aheadworks\Sarp2\Api\SubscriptionPriceCalculationInterface;
use Aheadworks\Sarp2\Model\Config\AdvancedPricingValueResolver;
use Aheadworks\Sarp2\Model\Product\Subscription\Price\Calculation\PriceResolver as ProductPriceResolver;
use Aheadworks\Sarp2\Model\Product\Subscription\Price\Calculation\SubscriptionPriceCalculator;

/**
 * Class Calculation
 *
 * @package Aheadworks\Sarp2\Model\Product\Subscription\Price
 */
class Calculation implements SubscriptionPriceCalculationInterface
{
    /**
     * @var AdvancedPricingValueResolver
     */
    private $advancedPricingConfigValueResolver;

    /**
     * @var SubscriptionPriceCalculator
     */
    private $calculator;

    /**
     * @var ProductPriceResolver
     */
    private $productPriceResolver;

    /**
     * @param AdvancedPricingValueResolver $advancedPricingConfigValueResolver
     * @param SubscriptionPriceCalculator $priceCalculator
     * @param ProductPriceResolver $productPriceResolver
     */
    public function __construct(
        AdvancedPricingValueResolver $advancedPricingConfigValueResolver,
        SubscriptionPriceCalculator $priceCalculator,
        ProductPriceResolver $productPriceResolver
    ) {
        $this->advancedPricingConfigValueResolver = $advancedPricingConfigValueResolver;
        $this->calculator = $priceCalculator;
        $this->productPriceResolver = $productPriceResolver;
    }

    /**
     * {@inheritdoc}
     */
    public function getTrialPrice($productId, $qty, $option)
    {
        if ($option->getIsAutoTrialPrice()) {
            $price = $this->calculator->calculateTrialPrice(
                $productId,
                $option->getPlanId(),
                $qty
            );
        } else {
            $price = $option->getTrialPrice();
        }

        return (float)$price;
    }

    /**
     * {@inheritdoc}
     */
    public function getRegularPrice($productId, $qty, $option)
    {
        if ($option->getIsAutoRegularPrice()) {
            $price = $this->calculator->calculateRegularPrice(
                $productId,
                $option->getPlanId(),
                $qty
            );
        } else {
            if ($this->advancedPricingConfigValueResolver->isUsedAdvancePricing($productId)) {
                $fixedOptionPrice = $option->getRegularPrice();
                $productBasePrice = $this->productPriceResolver->getPrice($productId, $qty);
                $price = min($fixedOptionPrice, $productBasePrice);
            } else {
                $price = $option->getRegularPrice();
            }
        }

        return (float)$price;
    }
}
