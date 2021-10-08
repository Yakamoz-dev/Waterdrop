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
namespace Aheadworks\Sarp2\Model\Product\Subscription\Price;

use Aheadworks\Sarp2\Api\SubscriptionPriceCalculatorInterface;
use Aheadworks\Sarp2\Model\Config\AdvancedPricingValueResolver;
use Aheadworks\Sarp2\Model\Product\Subscription\Price\Calculation\ProductPriceResolver as ProductPriceResolver;
use Aheadworks\Sarp2\Model\Product\Subscription\Price\Calculation\PeriodPriceCalculator;

/**
 * Class Calculator
 */
class Calculator implements SubscriptionPriceCalculatorInterface
{
    /**
     * @var AdvancedPricingValueResolver
     */
    private $advancedPricingConfigValueResolver;

    /**
     * @var PeriodPriceCalculator
     */
    private $byPeriodCalculator;

    /**
     * @var ProductPriceResolver
     */
    private $productPriceResolver;

    /**
     * @param AdvancedPricingValueResolver $advancedPricingConfigValueResolver
     * @param PeriodPriceCalculator $priceCalculator
     * @param ProductPriceResolver $productPriceResolver
     */
    public function __construct(
        AdvancedPricingValueResolver $advancedPricingConfigValueResolver,
        PeriodPriceCalculator $priceCalculator,
        ProductPriceResolver $productPriceResolver
    ) {
        $this->advancedPricingConfigValueResolver = $advancedPricingConfigValueResolver;
        $this->byPeriodCalculator = $priceCalculator;
        $this->productPriceResolver = $productPriceResolver;
    }

    /**
     * {@inheritdoc}
     */
    public function getTrialPrice($calculationInput, $option)
    {
        if ($option->getIsAutoTrialPrice()) {
            $price = $this->byPeriodCalculator->calculateTrialPrice(
                $calculationInput,
                $option->getPlanId()
            );
        } else {
            $price = $option->getTrialPrice();
        }

        return (float)$price;
    }

    /**
     * {@inheritdoc}
     */
    public function getRegularPrice($calculationInput, $option)
    {
        if ($option->getIsAutoRegularPrice()) {
            $price = $this->byPeriodCalculator->calculateRegularPrice(
                $calculationInput,
                $option->getPlanId()
            );
        } else {
            $isUseAdvancedPricing = $this->advancedPricingConfigValueResolver->isUsedAdvancePricing(
                $calculationInput->getProduct()->getId()
            );
            if ($isUseAdvancedPricing) {
                $fixedOptionPrice = $option->getRegularPrice();
                $productBasePrice = $this->productPriceResolver->getPrice($calculationInput);
                $price = min($fixedOptionPrice, $productBasePrice);
            } else {
                $price = $option->getRegularPrice();
            }
        }

        return (float)$price;
    }
}
