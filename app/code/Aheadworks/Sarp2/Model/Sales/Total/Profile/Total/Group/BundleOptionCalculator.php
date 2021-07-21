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

use Aheadworks\Sarp2\Api\Data\ProfileItemInterface;
use Aheadworks\Sarp2\Model\Product\Subscription\Price\Calculation\Calculator;
use Aheadworks\Sarp2\Model\Profile\Item\Checker\IsChildrenCalculated;
use Magento\Bundle\Model\Product\Type as BundleType;
use Magento\Catalog\Model\Product;
use Magento\Framework\DataObjectFactory;
use Magento\Framework\Pricing\PriceCurrencyInterface;

/**
 * Class BundleOptionCalculator
 *
 * @package Aheadworks\Sarp2\Model\Sales\Total\Profile\Total\Group
 */
class BundleOptionCalculator
{
    /**
     * @var PriceCurrencyInterface
     */
    private $priceCurrency;

    /**
     * @var DataObjectFactory
     */
    private $dataObjectFactory;

    /**
     * @var IsChildrenCalculated
     */
    private $isChildCalculatedChecker;

    /**
     * @var Calculator
     */
    private $priceCalculator;

    /**
     * @param PriceCurrencyInterface $priceCurrency
     * @param DataObjectFactory $dataObjectFactory
     * @param IsChildrenCalculated $isChildCalculatedChecker
     * @param Calculator $priceCalculator
     */
    public function __construct(
        PriceCurrencyInterface $priceCurrency,
        DataObjectFactory $dataObjectFactory,
        IsChildrenCalculated $isChildCalculatedChecker,
        Calculator $priceCalculator
    ) {
        $this->priceCurrency = $priceCurrency;
        $this->dataObjectFactory = $dataObjectFactory;
        $this->isChildCalculatedChecker = $isChildCalculatedChecker;
        $this->priceCalculator = $priceCalculator;
    }

    /**
     * Apply bundle options price
     *
     * @param ProfileItemInterface $item
     * @param float $basePrice
     * @param int $planId
     * @param bool $useBaseCurrency
     * @param string $dataResolverStartegyType
     * @return float
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function applyBundlePrice($item, $basePrice, $planId, $useBaseCurrency, $dataResolverStartegyType)
    {
        if ($item->getProductType() != BundleType::TYPE_CODE) {
            return $basePrice;
        }

        $product = $this->getBundleProduct($item);
        $bundleItemsPrice = $product->getPriceModel()->getTotalBundleItemsPrice(
            $product,
            $item->getQty()
        );

        if (!$useBaseCurrency) {
            $bundleItemsPrice = $this->priceCurrency->convert($bundleItemsPrice);
        }

        $bundleItemsPrice = $this->priceCalculator->calculateAccordingPlan(
            $bundleItemsPrice,
            $planId,
            $dataResolverStartegyType
        );

        return $basePrice + $bundleItemsPrice;
    }

    /**
     * Get bundle product with calculated
     *
     * @param ProfileItemInterface $item
     * @return Product|null
     */
    private function getBundleProduct($item)
    {
        $productClone = clone $item->getProduct();
        $itemOptions = $item->getProductOptions();
        $buyRequest = $this->dataObjectFactory->create([
            'data' => $itemOptions['info_buyRequest'] ?? []
        ]);

        $candidates = $productClone->getTypeInstance()->processConfiguration(
            $buyRequest,
            $productClone,
            $productClone->getTypeInstance()::PROCESS_MODE_FULL
        );

        foreach ($candidates as $candidate) {
            if ($candidate->getTypeId() == BundleType::TYPE_CODE) {
                return $candidate;
            }
        }

        return null;
    }
}
