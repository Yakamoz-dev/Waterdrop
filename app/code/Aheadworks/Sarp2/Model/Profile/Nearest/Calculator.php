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
namespace Aheadworks\Sarp2\Model\Profile\Nearest;

use Aheadworks\Sarp2\Api\Data\ProfileInterface;
use Aheadworks\Sarp2\Engine\PaymentInterface;
use Aheadworks\Sarp2\Model\ProfileManagement;
use Magento\Directory\Model\Currency;
use Magento\Directory\Model\CurrencyFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Pricing\PriceCurrencyInterface;
use Magento\Quote\Model\Quote;

/**
 * Class Calculator
 * @package Aheadworks\Sarp2\Model\Profile\Nearest
 */
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
     * @var CurrencyFactory
     */
    private $currencyFactory;

    /**
     * @param PriceCurrencyInterface $priceCurrency
     * @param CurrencyFactory $currencyFactory
     * @param ProfileManagement $profileManagement
     */
    public function __construct(
        PriceCurrencyInterface $priceCurrency,
        CurrencyFactory $currencyFactory,
        ProfileManagement $profileManagement
    ) {
        $this->profileManagement = $profileManagement;
        $this->priceCurrency = $priceCurrency;
        $this->currencyFactory = $currencyFactory;
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

        foreach ($quoteItems as $quoteItem) {
            $total += $quoteItem->getPrice() * $quoteItem->getQty();
        }

        $total = $this->priceCurrency->convert($total);

        $nextPaymentInfo = $this->profileManagement->getNextPaymentInfo($profile->getProfileId());
        $profileTotal = $nextPaymentInfo->getPaymentPeriod() == PaymentInterface::PERIOD_TRIAL
            ? $profile->getTrialSubtotal()
            : $profile->getRegularSubtotal();

        $currency = $this->priceCurrency->getCurrency();
        $currencyCode = $currency->getCode();
        $profileCurrencyCode = $profile->getProfileCurrencyCode();

        if ($currencyCode != $profileCurrencyCode) {
            /** @var Currency $profileCurrency */
            $profileCurrency = $this->currencyFactory->create();
            $profileCurrency->load($profileCurrencyCode);
            $profileTotal = $profileCurrency->convert($profileTotal, $currencyCode);
        }

        return $profileTotal + $total;
    }
}
