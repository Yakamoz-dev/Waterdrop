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
namespace Aheadworks\Sarp2\Model\Sales\Total\Quote\Total\Group;

use Aheadworks\Sarp2\Api\SubscriptionOptionRepositoryInterface;
use Aheadworks\Sarp2\Api\SubscriptionPriceCalculationInterface;
use Aheadworks\Sarp2\Model\Plan\Resolver\ByPeriod\StrategyPool;
use Aheadworks\Sarp2\Model\Sales\Total\Group\AbstractGroup;
use Aheadworks\Sarp2\Model\Sales\Total\PopulatorFactory;
use Aheadworks\Sarp2\Model\Sales\Total\ProviderInterface;
use Magento\Bundle\Model\Product\Type as BundleType;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Model\Product;
use Magento\Framework\Pricing\PriceCurrencyInterface;
use Magento\Quote\Model\Quote\Item\AbstractItem;

/**
 * Class Regular
 * @package Aheadworks\Sarp2\Model\Sales\Total\Quote\Total\Group
 */
class Regular extends AbstractGroup
{
    /**
     * @var CustomOptionCalculator
     */
    private $customOptionsCalculator;

    /**
     * @var BundleOptionCalculator
     */
    private $bundleOptionsCalculator;

    /**
     * @param SubscriptionOptionRepositoryInterface $optionRepository
     * @param SubscriptionPriceCalculationInterface $priceCalculation
     * @param PriceCurrencyInterface $priceCurrency
     * @param PopulatorFactory $populatorFactory
     * @param ProviderInterface $provider
     * @param CustomOptionCalculator $customOptionCalculator
     * @param BundleOptionCalculator $bundleOptionCalculator
     * @param array $populateMaps
     */
    public function __construct(
        SubscriptionOptionRepositoryInterface $optionRepository,
        SubscriptionPriceCalculationInterface $priceCalculation,
        PriceCurrencyInterface $priceCurrency,
        PopulatorFactory $populatorFactory,
        ProviderInterface $provider,
        CustomOptionCalculator $customOptionCalculator,
        BundleOptionCalculator $bundleOptionCalculator,
        array $populateMaps = []
    ) {
        parent::__construct(
            $optionRepository,
            $priceCalculation,
            $priceCurrency,
            $populatorFactory,
            $provider,
            $populateMaps
        );
        $this->customOptionsCalculator = $customOptionCalculator;
        $this->bundleOptionsCalculator = $bundleOptionCalculator;
    }

    /**
     * {@inheritdoc}
     */
    public function getCode()
    {
        return self::CODE_REGULAR;
    }

    /**
     * {@inheritdoc}
     */
    public function getItemPrice($item, $useBaseCurrency)
    {
        $result = 0.0;
        $optionId = $item->getOptionByCode('aw_sarp2_subscription_type');
        if ($optionId) {
            $option = $this->optionRepository->get($optionId->getValue());
            $product = $this->getProduct($item);

            $baseItemPrice = $this->priceCalculation->getRegularPrice(
                $product->getEntityId(),
                $item->getQty(),
                $option
            );
            $result = $useBaseCurrency
                ? $baseItemPrice
                : $this->priceCurrency->convert($baseItemPrice);

            $result = $this->bundleOptionsCalculator->applyBundlePrice(
                $item,
                $result,
                $option->getPlanId(),
                $useBaseCurrency,
                StrategyPool::TYPE_REGULAR
            );
        }

        $result = $this->customOptionsCalculator->applyOptionsPrice($item, $result, $useBaseCurrency, false);

        return $result;
    }

    /**
     * Get product
     *
     * @param ItemInterface|AbstractItem $item
     * @return ProductInterface|Product
     */
    private function getProduct($item)
    {
        if ($item instanceof AbstractItem
            && $item->getHasChildren()
            && $item->getProductType() != BundleType::TYPE_CODE
        ) {
            $children = $item->getChildren();
            $child = reset($children);
            $product = $child->getProduct();
        } else {
            $product = $item->getProduct();
        }
        return $product;
    }
}
