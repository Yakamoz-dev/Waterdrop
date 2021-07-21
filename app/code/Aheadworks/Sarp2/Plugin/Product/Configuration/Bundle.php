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
namespace Aheadworks\Sarp2\Plugin\Product\Configuration;

use Aheadworks\Sarp2\Model\Quote\Item\Checker\IsSubscription;
use Aheadworks\Sarp2\Model\Sales\Quote\Item\Option\BundleOptionPriceModifier;
use Magento\Bundle\Helper\Catalog\Product\Configuration as BundleConfiguration;
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
     * @param IsSubscription $isSubscriptionChecker
     * @param BundleOptionPriceModifier $optionProcessor
     */
    public function __construct(
        IsSubscription $isSubscriptionChecker,
        BundleOptionPriceModifier $optionProcessor
    ) {
        $this->isSubscriptionChecker = $isSubscriptionChecker;
        $this->optionPriceModifier = $optionProcessor;
    }

    /**
     * @param BundleConfiguration $subject
     * @param ItemInterface $item
     * @return float
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function afterGetSelectionFinalPrice(BundleConfiguration $subject, $result, $item)
    {
        return $this->isSubscriptionChecker->check($item)
            ? $this->optionPriceModifier->recalculateOptionPrice($item, $result)
            : $result;
    }
}
