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
namespace Aheadworks\Sarp2\Model\Profile\Nearest;

use Aheadworks\Sarp2\Api\Data\ProfileInterface;
use Aheadworks\Sarp2\Engine\PaymentInterface;
use Aheadworks\Sarp2\Model\ProfileManagement;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Pricing\PriceCurrencyInterface;
use Magento\Quote\Model\Quote;

class Calculator
{
    /**
     * @var ProfileManagement
     */
    private $profileManagement;

    /**
     * @var PriceCurrencyInterface
     */
    private $priceCurrency;

    /**
     * @param PriceCurrencyInterface $priceCurrency
     * @param ProfileManagement $profileManagement
     */
    public function __construct(
        PriceCurrencyInterface $priceCurrency,
        ProfileManagement $profileManagement
    ) {
        $this->profileManagement = $profileManagement;
        $this->priceCurrency = $priceCurrency;
    }

    /**
     * Calculate total for nearest profile
     *
     * @param Quote $quote
     * @param ProfileInterface $profile
     * @return float
     * @throws LocalizedException
     */
    public function calculateNearestProfileTotal(Quote $quote, ProfileInterface $profile): float
    {
        $total = 0.0;
        $quoteItems = $quote ? $quote->getItems() : [];
        $profileCurrencyCode = $profile->getProfileCurrencyCode();

        foreach ($quoteItems as $quoteItem) {
            $total += $quoteItem->getPrice() * $quoteItem->getQty();
        }

        $total = $this->priceCurrency->convertAndRound($total, null, $profileCurrencyCode);

        $nextPaymentInfo = $this->profileManagement->getNextPaymentInfo($profile->getProfileId());
        $profileTotal = $nextPaymentInfo->getPaymentPeriod() == PaymentInterface::PERIOD_TRIAL
            ? $profile->getTrialSubtotal()
            : $profile->getRegularSubtotal();

        return $profileTotal + $total;
    }
}
