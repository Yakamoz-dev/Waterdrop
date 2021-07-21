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
namespace Aheadworks\Sarp2\ViewModel\Subscription\Details;

use Aheadworks\Sarp2\Api\Data\PlanDefinitionInterface;
use Aheadworks\Sarp2\Api\Data\ProfileItemInterface as ProfileItem;
use Aheadworks\Sarp2\Api\ProfileRepositoryInterface;
use Aheadworks\Sarp2\Model\Profile\DateResolver as ProfileDateResolver;
use Aheadworks\Sarp2\Model\Profile\Details\Formatter as DetailsFormatter;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;
use Magento\Framework\View\Element\Block\ArgumentInterface;

/**
 * Class ForProfileItem
 *
 * @package Aheadworks\Sarp2\ViewModel\Subscription\Details
 */
class ForProfileItem implements ArgumentInterface
{
    /**
     * @var TimezoneInterface
     */
    private $localeDate;

    /**
     * @var DetailsFormatter
     */
    private $detailsFormatter;

    /**
     * @var ProfileDateResolver
     */
    private $profileDateResolver;

    /**
     * @var ProfileRepositoryInterface
     */
    private $profileRepository;

    /**
     * @param DetailsFormatter $detailsFormatter
     * @param TimezoneInterface $localeDate
     * @param ProfileDateResolver $profileDateResolver
     * @param ProfileRepositoryInterface $profileRepository
     */
    public function __construct(
        DetailsFormatter $detailsFormatter,
        TimezoneInterface $localeDate,
        ProfileDateResolver $profileDateResolver,
        ProfileRepositoryInterface $profileRepository
    ) {
        $this->detailsFormatter = $detailsFormatter;
        $this->localeDate = $localeDate;
        $this->profileDateResolver = $profileDateResolver;
        $this->profileRepository = $profileRepository;
    }

    /**
     * Check if show initial payment details
     *
     * @param ProfileItem $item
     * @return bool
     * @throws LocalizedException
     */
    public function isShowInitialDetails($item)
    {
        return $this->detailsFormatter->isShowInitialDetails(
            $this->getProfileDefinition($item)
        );
    }

    /**
     * Check if show trial period details
     *
     * @param ProfileItem $item
     * @return bool
     * @throws LocalizedException
     */
    public function isShowTrialDetails($item)
    {
        return $this->detailsFormatter->isShowTrialDetails(
            $this->getProfileDefinition($item)
        );
    }

    /**
     * Check if show regular period details
     *
     * @param ProfileItem $item
     * @return bool
     * @throws LocalizedException
     */
    public function isShowRegularDetails($item)
    {
        return $this->detailsFormatter->isShowRegularDetails(
            $this->getProfileDefinition($item)
        );
    }

    /**
     * Retrieve trial label
     *
     * @return string
     */
    public function getInitialLabel()
    {
        return $this->detailsFormatter->getInitialPaymentLabel();
    }

    /**
     * Retrieve trial label
     *
     * @param ProfileItem $item
     * @return string
     * @throws LocalizedException
     */
    public function getTrialLabel($item)
    {
        return $this->detailsFormatter->getTrialOfferLabel(
            $this->getProfileDefinition($item),
            $item->getTrialPrice()
        );
    }

    /**
     * Retrieve regular label
     *
     * @param ProfileItem $item
     * @return string
     * @throws LocalizedException
     */
    public function getRegularLabel($item)
    {
        return $this->detailsFormatter->getRegularOfferLabel(
            $this->getProfileDefinition($item)
        );
    }

    /**
     * Retrieve first payment price
     *
     * @param ProfileItem $item
     * @return string
     * @throws LocalizedException
     */
    public function getInitialPaymentPrice($item)
    {
        $profileDefinition = $this->getProfileDefinition($item);
        $fee = $item->getInitialFee();
        $firstPaymentAmount = $profileDefinition->getIsTrialPeriodEnabled()
            ? $item->getTrialPrice()
            : $item->getRegularPrice();
        $currencyCode = $this->getProfileCurrencyCode($item);

        return $this->detailsFormatter->getInitialPaymentPrice($fee, $firstPaymentAmount, $currencyCode);
    }

    /**
     * Retrieve trial price
     *
     * @param ProfileItem $item
     * @return string
     * @throws LocalizedException
     */
    public function getTrialPriceAndCycles($item)
    {
        return $this->detailsFormatter->getTrialPriceAndCycles(
            $item->getTrialPrice(),
            $this->getProfileDefinition($item),
            true,
            false,
            $this->getProfileCurrencyCode($item)
        );
    }

    /**
     * Retrieve regular price
     *
     * @param ProfileItem $item
     * @return string
     * @throws LocalizedException
     */
    public function getRegularPriceAndCycles($item)
    {
        return $this->detailsFormatter->getRegularPriceAndCycles(
            $item->getRegularPrice(),
            $this->getProfileDefinition($item),
            true,
            false,
            $this->getProfileCurrencyCode($item)
        );
    }

    /**
     * Retrieve trial start date
     *
     * @param ProfileItem $item
     * @return string
     * @throws LocalizedException
     */
    public function getTrialStartDate($item)
    {
        return $this->formatDate(
            $this->profileDateResolver->getTrialStartDate($item->getProfileId(), true)
        );
    }

    /**
     * Retrieve regular start date
     *
     * @param ProfileItem $item
     * @return string
     * @throws LocalizedException
     */
    public function getRegularStartDate($item)
    {
        return $this->formatDate(
            $this->profileDateResolver->getRegularStartDate(
                $item->getProfileId(),
                true
            )
        );
    }

    /**
     * Format date
     *
     * @param string $date
     * @return string
     */
    private function formatDate($date)
    {
        return $this->localeDate->formatDate($date, \IntlDateFormatter::MEDIUM);
    }

    /**
     * Retrieve profile definition by profile item
     *
     * @param ProfileItem $item
     * @return PlanDefinitionInterface
     * @throws LocalizedException
     */
    private function getProfileDefinition($item) {
        return $this->profileRepository
            ->get($item->getProfileId())
            ->getProfileDefinition();
    }

    /**
     * Retrieve profile currency code by profile item
     *
     * @param ProfileItem $item
     * @return string
     * @throws LocalizedException
     */
    private function getProfileCurrencyCode($item) {
        return $this->profileRepository
            ->get($item->getProfileId())
            ->getProfileCurrencyCode();
    }
}
