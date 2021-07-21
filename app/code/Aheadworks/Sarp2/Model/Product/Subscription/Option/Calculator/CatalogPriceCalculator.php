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

use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Pricing\PriceCurrencyInterface;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Helper\Data as CatalogHelper;

/**
 * Class PriceCalculator
 * @package Aheadworks\Sarp2\Model\Product\Subscription\Option\Processor
 */
class CatalogPriceCalculator
{
    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * @var CatalogHelper
     */
    private $catalogHelper;

    /**
     * @var PriceCurrencyInterface
     */
    private $priceCurrency;

    /**
     * @var Product[]
     */
    private $products;

    /**
     * @param ProductRepositoryInterface $productRepository
     * @param CatalogHelper $catalogHelper
     * @param PriceCurrencyInterface $priceCurrency
     */
    public function __construct(
        ProductRepositoryInterface $productRepository,
        CatalogHelper $catalogHelper,
        PriceCurrencyInterface $priceCurrency
    ) {
        $this->productRepository = $productRepository;
        $this->catalogHelper = $catalogHelper;
        $this->priceCurrency = $priceCurrency;
    }

    /**
     * Get formatted price
     *
     * @param int $productId
     * @param float $price
     * @param bool $exclTax
     * @param string|null $currency
     * @return string
     */
    public function getFormattedPrice($productId, $price, $exclTax = true, $currency = null)
    {
        return $this->priceCurrency->format(
            $this->getFinalPriceAmount($productId, $price, $exclTax),
            false,
            $this->priceCurrency::DEFAULT_PRECISION,
            null,
            $currency
        );
    }

    /**
     * Get old price
     *
     * @param float $price
     * @return float
     */
    public function getOldPriceAmount($price)
    {
        return $this->priceCurrency->convert($price);
    }

    /**
     * Get base price
     *
     * @param int $productId
     * @param float $price
     * @param string|null $currency
     * @return float
     */
    public function getBasePriceAmount($productId, $price, $currency = null)
    {
        $product = $this->getProduct($productId);
        if ($product) {
            $basePriceAmount = $this->catalogHelper->getTaxPrice(
                $product,
                $price,
                false,
                null,
                null,
                null,
                null,
                null,
                false
            );
            return $this->priceCurrency->convert($basePriceAmount, null, $currency);
        }
        return $price;
    }

    /**
     * Get final price
     *
     * @param int $productId
     * @param float $price
     * @param bool $exclTax
     * @param string|null $currency
     * @return float
     */
    public function getFinalPriceAmount($productId, $price, $exclTax = true, $currency = null)
    {
        $product = $this->getProduct($productId);
        $includingTax = !$exclTax;
        if ($product) {
            $finalPrice = $this->catalogHelper->getTaxPrice(
                $product,
                $price,
                $includingTax,
                null,
                null,
                null,
                null,
                null,
                false
            );
            return $this->priceCurrency->convert($finalPrice, null, $currency);
        }
        return $price;
    }

    /**
     * Get product
     *
     * @param $productId
     * @return \Magento\Catalog\Api\Data\ProductInterface|Product
     */
    private function getProduct($productId)
    {
        if (!isset($this->products[$productId])) {
            try {
                $this->products[$productId] = $this->productRepository->getById($productId);
            } catch (NoSuchEntityException $e) {
                unset($this->products[$productId]);
            }
        }

        return $this->products[$productId];
    }
}
