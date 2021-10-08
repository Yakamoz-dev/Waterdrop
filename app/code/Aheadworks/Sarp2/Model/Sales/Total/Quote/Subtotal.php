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
namespace Aheadworks\Sarp2\Model\Sales\Total\Quote;

use Aheadworks\Sarp2\Model\Quote\Item\Checker\IsSubscription;
use Aheadworks\Sarp2\Model\Sales\Total\Quote\Subtotal\PrePayment\Calculation;
use Magento\Quote\Model\Quote\Address\Total\Subtotal as Collector;
use Magento\Quote\Model\QuoteValidator;

/**
 * Class Subtotal
 * @package Aheadworks\Sarp2\Model\Sales\Total\Quote
 */
class Subtotal extends Collector
{
    /**
     * @var IsSubscription
     */
    private $isSubscriptionChecker;

    /**
     * @var Calculation
     */
    private $calculation;

    /**
     * @param QuoteValidator $quoteValidator
     * @param IsSubscription $isSubscriptionChecker
     * @param Calculation $calculation
     */
    public function __construct(
        QuoteValidator $quoteValidator,
        IsSubscription $isSubscriptionChecker,
        Calculation $calculation
    ) {
        parent::__construct($quoteValidator);
        $this->isSubscriptionChecker = $isSubscriptionChecker;
        $this->calculation = $calculation;
    }

    /**
     * {@inheritdoc}
     */
    protected function _calculateRowTotal($item, $finalPrice, $originalPrice)
    {
        if ($this->isSubscriptionChecker->check($item)) {
            $calcResult = $this->calculation->calculateItemPrice($item, false);
            $baseCalcResult = $this->calculation->calculateItemPrice($item, true);

            $price = $calcResult->getAmount();
            $basePrice = $baseCalcResult->getAmount();
            $item->setPrice($price)
                ->setBasePrice($basePrice)
                ->setOriginalPrice($price)
                ->setBaseOriginalPrice($basePrice)
                ->setCalculationPrice($price)
                ->setBaseCalculationPrice($basePrice)
                ->setAwSarpIsPriceInclInitialFeeAmount($calcResult->isInitialFeeSummed())
                ->setAwSarpIsPriceInclTrialAmount($calcResult->isTrialPriceSummed())
                ->setAwSarpIsPriceInclRegularAmount($calcResult->isRegularPriceSummed());
            $item->calcRowTotal();
        } else {
            parent::_calculateRowTotal($item, $finalPrice, $originalPrice);
        }
        return $this;
    }
}
