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
namespace Aheadworks\Sarp2\Model\Product\Subscription\Option\Calculator;

use Aheadworks\Sarp2\Api\Data\SubscriptionOptionInterface;
use Aheadworks\Sarp2\Api\SubscriptionPriceCalculationInterface;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Pricing\Price\TierPrice;
use Magento\Framework\Locale\Format;
use Magento\Framework\Pricing\PriceCurrencyInterface;

/**
 * Class TierPriceCalculator
 *
 * @package Aheadworks\Sarp2\Model\Product\Subscription\Option\Processor
 */
class TierPriceCalculator
{
    /**
     * @var SubscriptionPriceCalculationInterface
     */
    private $subscriptionPriceCalculator;

    /**
     * @var Format
     */
    private $localeFormat;

    /**
     * @var PriceCurrencyInterface
     */
    private $priceCurrency;

    /**
     * @param SubscriptionPriceCalculationInterface $calculation
     * @param Format $localeFormat
     * @param PriceCurrencyInterface $priceCurrency
     */
    public function __construct(
        SubscriptionPriceCalculationInterface $calculation,
        Format $localeFormat,
        PriceCurrencyInterface $priceCurrency
    ) {
        $this->subscriptionPriceCalculator = $calculation;
        $this->localeFormat = $localeFormat;
        $this->priceCurrency = $priceCurrency;
    }

    /**
     * Get regular product tier prices
     *
     * @param ProductInterface|Product $product
     * @return array
     */
    public function getRegularTierPrices($product)
    {
        $tierPrices = [];
        $priceInfo = $product->getPriceInfo();
        $tierPriceModel = $priceInfo->getPrice(TierPrice::PRICE_CODE);
        $tierPricesList = $tierPriceModel->getTierPriceList();
        foreach ($tierPricesList as $tierPrice) {
            $percentage = null === $tierPrice['percentage_value']
                ? $tierPriceModel->getSavePercent($tierPrice['price'])
                : $tierPrice['percentage_value'];
            $tierPrices[] = [
                'qty' => $tierPrice['price_qty'],
                'price' => $tierPrice['website_price'],
                'percentage' => $this->localeFormat->getNumber($percentage)
            ];
        }

        return $tierPrices;
    }

    /**
     * Calculate subscription tier prices
     *
     * @param ProductInterface|Product $product
     * @param SubscriptionOptionInterface $option
     * @return array
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function calculateSubscriptionTierPrices($product, $option)
    {
        $tierPrices = [];
        $priceInfo = $product->getPriceInfo();
        $tierPriceModel = $priceInfo->getPrice(TierPrice::PRICE_CODE);
        $tierPricesList = $tierPriceModel->getTierPriceList();
        foreach ($tierPricesList as $tierPrice) {
            $qty = $tierPrice['price_qty'];
            $price = $this->subscriptionPriceCalculator->getRegularPrice(
                $product->getId(),
                $qty,
                $option
            );

            // recalculate percent
            $basePrice = $this->subscriptionPriceCalculator->getRegularPrice(
                $product->getId(),
                1,
                $option
            );
            if ($basePrice > 0) {
                $percent = round(
                    100 - ((100 / $basePrice) * $price)
                );
            } else {
                $percent = 0;
            }

            if ($percent > 0) {
                $tierPrices[] = [
                    'qty' => $qty,
                    'price' => $this->priceCurrency->convert($price),
                    'percentage' => $this->localeFormat->getNumber($percent)
                ];
            }
        }

        return $tierPrices;
    }
}
