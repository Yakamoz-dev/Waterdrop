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
use Aheadworks\Sarp2\Api\Data\SubscriptionOptionInterface;
use Aheadworks\Sarp2\Api\PlanRepositoryInterface;
use Aheadworks\Sarp2\Api\SubscriptionOptionRepositoryInterface;
use Aheadworks\Sarp2\Model\Product\Subscription\Details\Config\ProcessorInterface;
use Aheadworks\Sarp2\Model\Product\Subscription\Details\Config\ProviderInterface;
use Aheadworks\Sarp2\Model\Product\Subscription\Option\Processor as SubscriptionOptionProcessor;
use Aheadworks\Sarp2\Model\Config\AdvancedPricingValueResolver;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Tax\Helper\Data as TaxHelper;

/**
 * Class Generic
 * @package Aheadworks\Sarp2\Model\Product\Subscription\Details\Config
 */
class Generic implements ProviderInterface
{
    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * @var SubscriptionOptionRepositoryInterface
     */
    private $optionsRepository;

    /**
     * @var PlanRepositoryInterface
     */
    private $planRepository;

    /**
     * @var SubscriptionOptionProcessor
     */
    private $subscriptionOptionProcessor;

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
     * @param ProductRepositoryInterface $productRepository
     * @param SubscriptionOptionRepositoryInterface $optionsRepository
     * @param PlanRepositoryInterface $planRepository
     * @param SubscriptionOptionProcessor $subscriptionOptionProcessor
     * @param TaxHelper $taxHelper
     * @param AdvancedPricingValueResolver $advancedPricingConfigValueResolver
     * @param StoreManagerInterface $storeManagement
     * @param ProcessorInterface|null $processorComposite
     */
    public function __construct(
        ProductRepositoryInterface $productRepository,
        SubscriptionOptionRepositoryInterface $optionsRepository,
        PlanRepositoryInterface $planRepository,
        SubscriptionOptionProcessor $subscriptionOptionProcessor,
        TaxHelper $taxHelper,
        AdvancedPricingValueResolver $advancedPricingConfigValueResolver,
        StoreManagerInterface $storeManagement,
        ProcessorInterface $processorComposite = null
    ) {
        $this->productRepository = $productRepository;
        $this->optionsRepository = $optionsRepository;
        $this->planRepository = $planRepository;
        $this->subscriptionOptionProcessor = $subscriptionOptionProcessor;
        $this->taxHelper = $taxHelper;
        $this->advancedPricingConfigValueResolver = $advancedPricingConfigValueResolver;
        $this->storeManagement = $storeManagement;
        $this->processorComposite = $processorComposite;
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
                'isUsedAdvancedPricing' => $this->advancedPricingConfigValueResolver->isUsedAdvancePricing($productId),
                'productType' => $productTypeId,
                'productId' => $productId,
                'selectedSubscriptionOptionId' => null,
                'currencyFormat' => $this->storeManagement->getStore()->getCurrentCurrency()->getOutputFormat()
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
        $config = [];

        $subscriptionOptions = $this->optionsRepository->getList($productId);
        /** @var SubscriptionOptionInterface $option */
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
            $optionId = $item ? $option->getPlanId() : $option->getOptionId();
            $config[$optionId] = $detailedOptions;
        }
        return $config;
    }

    /**
     * Get regular prices config
     *
     * @param int $productId
     * @param ProfileItemInterface|null $item
     * @return array
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    private function getRegularPricesConfig($productId, $item = null)
    {
        $product = $this->productRepository->getById($productId);

        $priceOptions = [0 => $this->subscriptionOptionProcessor->getProductPrices($product)];

        $subscriptionOptions = $this->optionsRepository->getList($productId);
        foreach ($subscriptionOptions as $option) {
            $planDefinition = $this->planRepository->get($option->getPlanId())->getDefinition();
            $priceOptions[$option->getOptionId()] = $this->subscriptionOptionProcessor->getOptionPrices(
                $option,
                $planDefinition,
                $this->taxHelper->displayPriceExcludingTax(),
                $item
            );
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
        $config = [];

        $subscriptionOptions = $this->optionsRepository->getList($productId);
        foreach ($subscriptionOptions as $option) {
            $planDefinition = $this->planRepository->get($option->getPlanId())->getDefinition();
            $billingCycles = $planDefinition->getTotalBillingCycles();
            $config[$option->getOptionId()] = [
                'enabled' => $option->getIsInstallmentsMode() && $billingCycles > 0,
                'billingCycles' => $billingCycles,
                'isTrial' => (bool)$planDefinition->getIsTrialPeriodEnabled()
            ];
        }
        return $config;
    }
}
