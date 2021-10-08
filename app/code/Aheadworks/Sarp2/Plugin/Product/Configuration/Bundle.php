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
 * @version    2.15.3
 * @copyright  Copyright (c) 2021 Aheadworks Inc. (https://aheadworks.com/)
 * @license    https://aheadworks.com/end-user-license-agreement/
 */
namespace Aheadworks\Sarp2\Plugin\Product\Configuration;

use Aheadworks\Sarp2\Model\Config\AdvancedPricingValueResolver;
use Aheadworks\Sarp2\Model\Product\Type\Bundle\PriceModelSubstitute;
use Aheadworks\Sarp2\Model\Quote\Item\Checker\IsSubscription;
use Aheadworks\Sarp2\Model\Sales\Quote\Item\Option\BundleOptionPriceModifier;
use Magento\Bundle\Helper\Catalog\Product\Configuration as BundleConfiguration;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\Product\Configuration\Item\ItemInterface;

/**
 * Class Bundle
 *
 * @package Aheadworks\Sarp2\Plugin\Product\Configuration
 */
class Bundle
{
    /**
     * @var IsSubscription
     */
    private $isSubscriptionChecker;

    /**
     * @var BundleOptionPriceModifier
     */
    private $optionPriceModifier;

    /**
     * @var AdvancedPricingValueResolver
     */
    private $advancedPricingValueResolver;

    /**
     * @param IsSubscription $isSubscriptionChecker
     * @param BundleOptionPriceModifier $optionProcessor
     * @param AdvancedPricingValueResolver $advancedPricingValueResolver
     */
    public function __construct(
        IsSubscription $isSubscriptionChecker,
        BundleOptionPriceModifier $optionProcessor,
        AdvancedPricingValueResolver $advancedPricingValueResolver
    ) {
        $this->isSubscriptionChecker = $isSubscriptionChecker;
        $this->optionPriceModifier = $optionProcessor;
        $this->advancedPricingValueResolver = $advancedPricingValueResolver;
    }

    /**
     * @param BundleConfiguration $subject
     * @param callable $proceed
     * @param ItemInterface $item
     * @param Product $selectionProduct
     * @return float
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function aroundGetSelectionFinalPrice(
        BundleConfiguration $subject,
        callable $proceed,
        ItemInterface $item,
        Product $selectionProduct
    ) {
        $product = $item->getProduct();
        if ($this->isSubscriptionChecker->check($item)
            && !$this->advancedPricingValueResolver->isUsedAdvancePricing($product->getId())
        ) {
            $product->setData(PriceModelSubstitute::DO_NOT_USE_ADVANCED_PRICES_FOR_BUNDLE, true);
        }

        $finalPrice = $proceed($item, $selectionProduct);
        $finalPrice = $this->optionPriceModifier->recalculateOptionPrice($item, $finalPrice);

        return $finalPrice;
    }
}
