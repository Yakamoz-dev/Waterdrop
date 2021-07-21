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
namespace Aheadworks\Sarp2\Model\Product\Subscription\Details\Config\Provider;

use Aheadworks\Sarp2\Api\Data\ProfileItemInterface;
use Aheadworks\Sarp2\Api\PlanRepositoryInterface;
use Aheadworks\Sarp2\Model\Config;
use Aheadworks\Sarp2\Model\Product\Subscription\Details\Config\ProcessorInterface;
use Aheadworks\Sarp2\Model\Product\Subscription\Details\Config\Provider\Configurable\ChildProcessor;
use Aheadworks\Sarp2\Model\Product\Subscription\Details\Config\ProviderInterface;
use Aheadworks\Sarp2\Model\Product\Subscription\Option\Processor as SubscriptionOptionProcessor;
use Aheadworks\Sarp2\Model\Config\AdvancedPricingValueResolver;
use Aheadworks\Sarp2\Model\Product\Subscription\Price\AsLowAsCalculator;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Model\Product;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Tax\Helper\Data as TaxHelper;

/**
 * Class Configurable
 * @package Aheadworks\Sarp2\Model\Product\Subscription\Details\Config
 */
class Configurable implements ProviderInterface
{
    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * @var PlanRepositoryInterface
     */
    private $planRepository;

    /**
     * @var SubscriptionOptionProcessor
     */
    private $subscriptionOptionProcessor;

    /**
     * @var ChildProcessor
     */
    private $childProcessor;

    /**
     * @var TaxHelper
     */
    private $taxHelper;

    /**
     * @var AdvancedPricingValueResolver
     */
    private $advancedPricingConfigValueResolver;

    /**
     * @var StoreManagerInterface
     */
    private $storeManagement;

    /**
     * @var ProcessorInterface
     */
    private $processorComposite;

    /**
     * @var AsLowAsCalculator
     */
    private $asLowAsCalculator;

    /**
     * @var Config
     */
    private $config;

    /**
     * @param ProductRepositoryInterface $productRepository
     * @param PlanRepositoryInterface $planRepository
     * @param SubscriptionOptionProcessor $subscriptionOptionProcessor
     * @param ChildProcessor $childProcessor
     * @param TaxHelper $taxHelper
     * @param AdvancedPricingValueResolver $advancedPricingConfigValueResolver
     * @param StoreManagerInterface $storeManagement
     * @param AsLowAsCalculator $asLowAsCalculator
     * @param Config $config
     * @param ProcessorInterface|null $processor
     */
    public function __construct(
        ProductRepositoryInterface $productRepository,
        PlanRepositoryInterface $planRepository,
        SubscriptionOptionProcessor $subscriptionOptionProcessor,
        ChildProcessor $childProcessor,
        TaxHelper $taxHelper,
        AdvancedPricingValueResolver $advancedPricingConfigValueResolver,
        StoreManagerInterface $storeManagement,
        AsLowAsCalculator $asLowAsCalculator,
        Config $config,
        ProcessorInterface $processor = null
    ) {
        $this->productRepository = $productRepository;
        $this->planRepository = $planRepository;
        $this->subscriptionOptionProcessor = $subscriptionOptionProcessor;
        $this->childProcessor = $childProcessor;
        $this->taxHelper = $taxHelper;
        $this->advancedPricingConfigValueResolver = $advancedPricingConfigValueResolver;
        $this->storeManagement = $storeManagement;
        $this->asLowAsCalculator = $asLowAsCalculator;
        $this->config = $config;
        $this->processorComposite = $processor;
    }

    /**
     * {@inheritdoc}
     */
    public function getConfig($productId, $productTypeId, $item = null)
    {
        try {
            $config = [
                'regularPrices' => $this->getRegularPricesConfig($productId, $item),
                'subscriptionDetails' => $this->getSubscriptionDetailsConfig($productId, $item),
                'installmentsMode' => $this->getInstallmentsModeConfig($productId),
                'isUsedAdvancedPricing' => $this->getIsUsedAdvancedPricingConfig($productId),
                'productType' => $productTypeId,
                'productId' => $productId,
                'currencyFormat' => $this->storeManagement->getStore()->getCurrentCurrency()->getOutputFormat(),
                'selectedSubscriptionOptionId' => null,
                'asLowAsPrice' => $this->getAsLowAsPrice($productId)
            ];
        } catch (\Exception $e) {
            $config = [];
        }

        if ($this->processorComposite) {
            $config = $this->processorComposite->process($config);
        }

        return $config;
    }

    /**
     * {@inheritdoc}
     */
    public function getSubscriptionDetailsConfig($productId, $item = null, $profile = null)
    {
        $product = $this->productRepository->getById($productId);
        $childProducts = $item
            ? $this->childProcessor->getProductByAttributes($product, $item)
            : $this->childProcessor->getAllowedList($product);
        $config = [];
        /** @var ProductInterface|Product $childProduct */
        foreach ($childProducts as $childProduct) {
            $subscriptionOptions = $this->childProcessor->getSubscriptionOptions($childProduct, $productId);
            foreach ($subscriptionOptions as $option) {
                if ($profile && $profile->getPlanId() == $option->getPlanId()) {
                    $planDefinition = $profile->getProfileDefinition();
                    /** @var ProfileItemInterface $item */
                    if ($item) {
                        $option->setTrialPrice(
                            $this->taxHelper->displayPriceIncludingTax() || $this->taxHelper->displayBothPrices()
                                ? $item->getTrialPriceInclTax()
                                : $item->getTrialPrice()
                        );
                        $option->setRegularPrice(
                            $this->taxHelper->displayPriceIncludingTax() || $this->taxHelper->displayBothPrices()
                                ? $item->getRegularPriceInclTax()
                                : $item->getRegularPrice()
                        );
                    }
                } else {
                    $planDefinition = $this->planRepository->get($option->getPlanId())->getDefinition();
                }

                $detailedOptions = $this->subscriptionOptionProcessor->getDetailedOptions(
                    $option,
                    $planDefinition,
                    $this->taxHelper->displayPriceExcludingTax(),
                    $profile,
                    $item
                );
                if ($item) {
                    $config[$option->getPlanId()] = $detailedOptions;
                } else {
                    $config[$option->getOptionId()][$childProduct->getId()] = $detailedOptions;
                }
            }
        }
        return $config;
    }

    /**
     * Get regular prices config
     *
     * @param int $productId
     * @param null $item
     * @return array
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    private function getRegularPricesConfig($productId, $item = null)
    {
        $product = $this->productRepository->getById($productId);
        $childProducts = $this->childProcessor->getAllowedList($product);
        $priceOptions = [];
        /** @var ProductInterface|Product $childProduct */
        foreach ($childProducts as $childProduct) {
            $productPrices = $this->subscriptionOptionProcessor->getProductPrices($childProduct);
            $priceOptions[0][$childProduct->getId()] = $productPrices;
            $subscriptionOptions = $this->childProcessor->getSubscriptionOptions($childProduct, $productId);
            foreach ($subscriptionOptions as $option) {
                $planDefinition = $this->planRepository->get($option->getPlanId())->getDefinition();
                $optionPrices = $this->subscriptionOptionProcessor->getOptionPrices(
                    $option,
                    $planDefinition,
                    $this->taxHelper->displayPriceExcludingTax(),
                    $item
                );
                $priceOptions[$option->getOptionId()][$childProduct->getId()] = $optionPrices;
            }
        }
        return [
            'productType' => $product->getTypeId(),
            'options' => $priceOptions
        ];
    }

    /**
     * Get installments mode config
     *
     * @param int $productId
     * @return array
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    private function getInstallmentsModeConfig($productId)
    {
        $product = $this->productRepository->getById($productId);
        $childProducts = $this->childProcessor->getAllowedList($product);
        $config = [];
        /** @var ProductInterface|Product $childProduct */
        foreach ($childProducts as $childProduct) {
            $subscriptionOptions = $this->childProcessor->getSubscriptionOptions($childProduct, $productId);
            foreach ($subscriptionOptions as $option) {
                $planDefinition = $this->planRepository->get($option->getPlanId())->getDefinition();
                $billingCycles = $planDefinition->getTotalBillingCycles();
                $config[$option->getOptionId()][$childProduct->getId()] = [
                    'enabled' => $option->getIsInstallmentsMode() && $billingCycles > 0,
                    'billingCycles' => $billingCycles,
                    'isTrial' => (bool)$planDefinition->getIsTrialPeriodEnabled()
                ];
            }
        }
        return $config;
    }

    /**
     * Get used advanced pricing config
     *
     * @param int $productId
     * @return array
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    private function getIsUsedAdvancedPricingConfig($productId)
    {
        $product = $this->productRepository->getById($productId);
        $childProducts = $this->childProcessor->getAllowedList($product);
        $config = [];
        /** @var ProductInterface|Product $childProduct */
        foreach ($childProducts as $childProduct) {
            $config[$childProduct->getId()] = $this->advancedPricingConfigValueResolver->isUsedAdvancePricing(
                $childProduct->getId()
            );
        }
        return $config;
    }

    /**
     * Get As Low As subscription price
     *
     * @param $productId
     * @return array|null
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    private function getAsLowAsPrice($productId)
    {
        // todo: M2SARP2-990 Hide "As Low As" price in release 2.12
        return $this->config->isUsedSubscriptionPriceInAsLowAs()
            ? $this->asLowAsCalculator->calculate($productId)
            : null;
    }
}
