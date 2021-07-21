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

use Aheadworks\Sarp2\Model\Config\AdvancedPricingValueResolver;
use Magento\Catalog\Api\ProductRepositoryInterface;

/**
 * Class PriceResolver
 *
 * @package Aheadworks\Sarp2\Model\Product\Subscription\Price\Calculation
 */
class PriceResolver
{
    /**
     * @var AdvancedPricingValueResolver
     */
    private $advancedPricingValueResolver;

    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * PriceProvider constructor.
     *
     * @param AdvancedPricingValueResolver $advancedPricingValueResolver
     * @param ProductRepositoryInterface $productRepository
     */
    public function __construct(
        AdvancedPricingValueResolver $advancedPricingValueResolver,
        ProductRepositoryInterface $productRepository
    ) {
        $this->advancedPricingValueResolver = $advancedPricingValueResolver;
        $this->productRepository = $productRepository;
    }

    /**
     * Get product price
     *
     * @param int $productId
     * @param float $qty
     * @param bool|null $forceUseAdvancedConfig
     * @return float
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getPrice($productId, $qty, $forceUseAdvancedConfig = null)
    {
        $product = $this->productRepository->getById($productId);
        $isUsedAdvancePricing = $this->advancedPricingValueResolver->isUsedAdvancePricing($product->getId());
        if (null !== $forceUseAdvancedConfig) {
            $isUsedAdvancePricing = $forceUseAdvancedConfig;
        }
        return $isUsedAdvancePricing
            ? $product->getFinalPrice($qty)
            : $product->getPrice();
    }
}
