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
namespace Aheadworks\Sarp2\Model\Product\Subscription\Price\Calculation;

use Aheadworks\Sarp2\Api\PlanRepositoryInterface;
use Aheadworks\Sarp2\Model\Plan\Resolver\ByPeriod\StrategyPool;

/**
 * Class SubscriptionPriceCalculator
 *
 * @package Aheadworks\Sarp2\Model\Product\Subscription\Price\Calculation
 */
class SubscriptionPriceCalculator
{
    /**
     * @var PlanRepositoryInterface
     */
    private $planRepository;

    /**
     * @var Calculator
     */
    private $baseCalculator;

    /**
     * @var PriceResolver
     */
    private $priceResolver;

    /**
     * @param PlanRepositoryInterface $planRepository
     * @param Calculator $amountCalculator
     * @param PriceResolver $priceResolver
     */
    public function __construct(
        PlanRepositoryInterface $planRepository,
        Calculator $amountCalculator,
        PriceResolver $priceResolver
    ) {
        $this->planRepository = $planRepository;
        $this->baseCalculator = $amountCalculator;
        $this->priceResolver = $priceResolver;
    }

    /**
     * Calculate subscription price for trial subscription period
     *
     * @param int $productId
     * @param int $planId
     * @param float $qty
     * @param null $forceUseAdvancedConfig
     * @return float
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function calculateTrialPrice($productId, $planId, $qty, $forceUseAdvancedConfig = null)
    {
        return $this->calculatePrice(
            $productId,
            $planId,
            $qty,
            StrategyPool::TYPE_TRIAL,
            $forceUseAdvancedConfig
        );
    }

    /**
     * Calculate subscription price for regular subscription period
     *
     * @param int $productId
     * @param int $planId
     * @param float $qty
     * @param null $forceUseAdvancedConfig
     * @return float
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function calculateRegularPrice($productId, $planId, $qty, $forceUseAdvancedConfig = null)
    {
        return $this->calculatePrice(
            $productId,
            $planId,
            $qty,
            StrategyPool::TYPE_REGULAR,
            $forceUseAdvancedConfig
        );
    }

    /**
     * Calculate subscription price for trial/regular subscription period
     *
     * @param int $productId
     * @param int $planId
     * @param float $qty
     * @param string $dataResolverStartegyType
     * @param null $forceUseAdvancedConfig
     * @return float
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function calculatePrice($productId, $planId, $qty, $dataResolverStartegyType, $forceUseAdvancedConfig = null)
    {
        $basePrice = $this->priceResolver->getPrice($productId, $qty, $forceUseAdvancedConfig);
        $subscriptionPrice = $this->baseCalculator->calculateAccordingPlan(
            $basePrice,
            $planId,
            $dataResolverStartegyType
        );

        return $subscriptionPrice;
    }
}
