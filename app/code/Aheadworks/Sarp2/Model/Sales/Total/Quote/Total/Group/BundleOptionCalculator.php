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

use Aheadworks\Sarp2\Model\Product\Subscription\Price\Calculation\Calculator;
use Magento\Bundle\Model\Product\Type as BundleType;
use Magento\Framework\Pricing\PriceCurrencyInterface;
use Magento\Quote\Api\Data\CartItemInterface;
use Magento\Quote\Model\Quote\Item\AbstractItem;

/**
 * Class BundleOptionCalculator
 *
 * @package Aheadworks\Sarp2\Model\Sales\Total\Quote\Total\Group
 */
class BundleOptionCalculator
{
    /**
     * @var PriceCurrencyInterface
     */
    private $priceCurrency;

    /**
     * @var Calculator
     */
    private $priceCalculator;

    /**
     * @param PriceCurrencyInterface $priceCurrency
     * @param Calculator $priceCalculator
     */
    public function __construct(
        PriceCurrencyInterface $priceCurrency,
        Calculator $priceCalculator
    ) {
        $this->priceCurrency = $priceCurrency;
        $this->priceCalculator = $priceCalculator;
    }

    /**
     * Apply bundle options price
     *
     * @param CartItemInterface|AbstractItem $item
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

        $qty = $item->getQty();
        $product = $item->getProduct();
        $priceModel = $product->getPriceModel();
        $bundleItemsPrice = $priceModel->getTotalBundleItemsPrice($product, $qty);

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
}
