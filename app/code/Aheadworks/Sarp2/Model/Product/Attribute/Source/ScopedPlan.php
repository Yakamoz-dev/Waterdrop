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
namespace Aheadworks\Sarp2\Model\Product\Attribute\Source;

use Aheadworks\Sarp2\Api\Data\PlanInterface;
use Aheadworks\Sarp2\Api\PlanRepositoryInterface;
use Aheadworks\Sarp2\Model\Plan\Source\Status;
use Aheadworks\Sarp2\Model\Product\Subscription\Price\Calculation\SubscriptionPriceCalculator as PriceCalculation;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Model\Locator\LocatorInterface;
use Magento\ConfigurableProduct\Model\Product\Type\Configurable;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\SortOrderBuilder;
use Magento\Framework\Data\OptionSourceInterface;

/**
 * Class ScopedPlan
 * @package Aheadworks\Sarp2\Model\Product\Attribute\Source
 */
class ScopedPlan implements OptionSourceInterface
{
    /**
     * @var PlanRepositoryInterface
     */
    private $planRepository;

    /**
     * @var PriceCalculation
     */
    private $priceCalculator;

    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;

    /**
     * @var SortOrderBuilder
     */
    private $sortOrderBuilder;

    /**
     * @var LocatorInterface
     */
    private $locator;

    /**
     * @var array
     */
    private $options = [];

    /**
     * @param PlanRepositoryInterface $planRepository
     * @param PriceCalculation $priceCalculation
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param SortOrderBuilder $sortOrderBuilder
     * @param LocatorInterface $locator
     */
    public function __construct(
        PlanRepositoryInterface $planRepository,
        PriceCalculation $priceCalculation,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        SortOrderBuilder $sortOrderBuilder,
        LocatorInterface $locator
    ) {
        $this->planRepository = $planRepository;
        $this->priceCalculator = $priceCalculation;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->sortOrderBuilder = $sortOrderBuilder;
        $this->locator = $locator;
    }

    /**
     * {@inheritdoc}
     */
    public function toOptionArray()
    {
        if (!$this->options) {
            foreach ($this->getPlans() as $plan) {
                $planId = $plan->getPlanId();
                $product = $this->getProduct();
                $definition = $plan->getDefinition();

                $params = [
                    'is_enabled' => $plan->getStatus() == Status::ENABLED,
                    'is_initial_fee_enabled' => $definition->getIsInitialFeeEnabled(),
                    'is_trial_price_enabled' => $definition->getIsTrialPeriodEnabled(),
                    'auto_trial_price' => $product ? $this->getAutoTrialPrice($product, $plan, false) : true,
                    'auto_regular_price' => $product ? $this->getAutoRegularPrice($product, $plan, false) : true,
                    'auto_trial_price_advanced' => $product ? $this->getAutoTrialPrice($product, $plan, true) : true,
                    'auto_regular_price_advanced' => $product ? $this->getAutoRegularPrice($product, $plan, true) : true
                ];
                $this->options[] = array_merge(
                    $params,
                    [
                        'value' => $planId,
                        'label' => $plan->getStatus() == Status::ENABLED
                            ? $plan->getName()
                            : __('%1 (disabled)', [$plan->getName()]),
                        'params' => array_combine(
                            array_map(
                                function ($k) {
                                    return 'data-'.$k;
                                },
                                array_keys($params)
                            ),
                            $params
                        )
                    ]
                );
            }
        }
        return $this->options;
    }

    /**
     * Retrieve product
     *
     * @return ProductInterface|null
     */
    private function getProduct()
    {
        try {
            $product = $this->locator->getProduct();
        } catch (\Exception $e) {
            $product = null;
        }
        return $product;
    }

    /**
     * Get subscription plan instances for current selected scope
     *
     * @return PlanInterface[]
     */
    private function getPlans()
    {
        $nameSortOrder = $this->sortOrderBuilder->setField(PlanInterface::NAME)
            ->setAscendingDirection()
            ->create();
        $this->searchCriteriaBuilder->addSortOrder($nameSortOrder);

        $plans = $this->planRepository->getList($this->searchCriteriaBuilder->create());
        return $plans->getItems();
    }

    /**
     * Get automatic calculated trial product price option displayed value
     *
     * @param ProductInterface $product
     * @param PlanInterface $plan
     * @param bool $useAdvanced
     * @return string|float
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    private function getAutoTrialPrice($product, $plan, $useAdvanced)
    {
        if ($product->getTypeId() == Configurable::TYPE_CODE) {
            return __('%1 of orig. price', $this->formatPercent($plan->getTrialPricePatternPercent()));
        } else {
            $productId = $product->getId();
            return $productId
                ? $this->priceCalculator->calculateTrialPrice($productId, $plan->getPlanId(), 1, $useAdvanced)
                : '';
        }
    }

    /**
     * Get automatic calculated regular product price option displayed value
     *
     * @param ProductInterface $product
     * @param PlanInterface $plan
     * @param bool $useAdvanced
     * @return string|float
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    private function getAutoRegularPrice($product, $plan, $useAdvanced)
    {
        if ($product->getTypeId() == Configurable::TYPE_CODE) {
            return __('%1 of orig. price', $this->formatPercent($plan->getRegularPricePatternPercent()));
        } else {
            $productId = $product->getId();
            return $productId
                ? $this->priceCalculator->calculateRegularPrice($productId, $plan->getPlanId(), 1, $useAdvanced)
                : '';
        }
    }

    /**
     * Format percent value
     *
     * @param float $value
     * @return string
     */
    private function formatPercent($value)
    {
        return sprintf('%.2F%%', $value);
    }
}
