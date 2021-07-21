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
namespace Aheadworks\Sarp2\Engine\Notification;

use Aheadworks\Sarp2\Api\Data\ProfileInterface;
use Aheadworks\Sarp2\Engine\Notification\DataResolver\ResolveSubject;
use Aheadworks\Sarp2\Engine\Notification\Offer\Extend\LinkBuilder as ExtendLinkBuilder;
use Aheadworks\Sarp2\Engine\PaymentInterface;
use Aheadworks\Sarp2\Engine\Profile\Action\Type\Extend\ValidatorWrapper;
use Aheadworks\Sarp2\Model\Email\Template\PriceFormatter;
use Aheadworks\Sarp2\Model\Plan\Resolver\TitleResolver;
use Magento\Framework\Stdlib\DateTime\DateTime as CoreDate;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;
use Magento\Store\Model\ScopeInterface;

/**
 * Class DataResolver
 * @package Aheadworks\Sarp2\Engine\Notification
 */
class DataResolver
{
    /**
     * @var CoreDate
     */
    private $coreDate;

    /**
     * @var TimezoneInterface
     */
    private $timezone;

    /**
     * @var PriceFormatter
     */
    private $priceFormatter;

    /**
     * @var TitleResolver
     */
    private $titleResolver;

    /**
     * @var ValidatorWrapper
     */
    private $extendActionValidator;

    /**
     * @var ExtendLinkBuilder
     */
    private $extendLinkBuilder;

    /**
     * @param CoreDate $coreDate
     * @param TimezoneInterface $timezone
     * @param PriceFormatter $priceFormatter
     * @param TitleResolver $titleResolver
     * @param ValidatorWrapper $extendActionValidator
     * @param ExtendLinkBuilder $extendLinkBuilder
     */
    public function __construct(
        CoreDate $coreDate,
        TimezoneInterface $timezone,
        PriceFormatter $priceFormatter,
        TitleResolver $titleResolver,
        ValidatorWrapper $extendActionValidator,
        ExtendLinkBuilder $extendLinkBuilder
    ) {
        $this->coreDate = $coreDate;
        $this->timezone = $timezone;
        $this->priceFormatter = $priceFormatter;
        $this->titleResolver = $titleResolver;
        $this->extendActionValidator = $extendActionValidator;
        $this->extendLinkBuilder = $extendLinkBuilder;
    }

    /**
     * Resolve notification data
     *
     * @param ResolveSubject $subject
     * @return array
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function resolve(ResolveSubject $subject)
    {
        $sourcePayment = $subject->getSourcePayment();

        $profile = $sourcePayment->getProfile();
        $currencyCode = $profile->getProfileCurrencyCode();
        $data = [
            'customerName' => $profile->getCustomerFullname(),
            'totalPaid' => $this->priceFormatter->format($sourcePayment->getTotalPaid(), $currencyCode),
            'totalScheduled' => $this->priceFormatter->format(
                $sourcePayment->getTotalScheduled(),
                $currencyCode
            ),
            'profileId' => $profile->getProfileId(),
            'incrementProfileId' => $profile->getIncrementId(),
            'orderId' => $sourcePayment->getOrderId(),
            'planName' => $this->titleResolver->getTitle($profile->getPlanId(), $profile->getStoreId())
        ];

        $nextPayments = $subject->getNextPayments();
        if (count($nextPayments)) {
            $nearestPayment = $this->getNearestPayment($nextPayments);
            $timezone = $this->timezone->getConfigTimezone(
                ScopeInterface::SCOPE_STORE,
                $profile->getStoreId()
            );

            $data = array_merge(
                $data,
                [
                    'nextPaymentDate' => $this->timezone->formatDateTime(
                        new \DateTime($nearestPayment->getScheduledAt()),
                        \IntlDateFormatter::SHORT,
                        \IntlDateFormatter::NONE,
                        null,
                        $timezone
                    ),
                    'nextPaymentTotalAmount' => $this->priceFormatter->format(
                        $nearestPayment->getTotalScheduled(),
                        $currencyCode
                    )
                ]
            );
        }

        $extendLink = $this->getExtendLink($profile);
        if ($extendLink) {
            $data['extendLink'] = $extendLink;
        }

        return $data;
    }

    /**
     * Retrieve extend subscription link if it possible
     *
     * @param ProfileInterface $profile
     * @return string|null
     */
    private function getExtendLink(ProfileInterface $profile)
    {
        if ($this->extendActionValidator->isValid($profile)) {
            return $this->extendLinkBuilder->build($profile);
        }

        return null;
    }

    /**
     * Get nearest payment
     *
     * @param PaymentInterface[] $nextPayments
     * @return PaymentInterface
     */
    private function getNearestPayment($nextPayments)
    {
        reset($nextPayments);
        /** @var PaymentInterface $nearestPayment */
        $nearestPayment = current($nextPayments);
        if (count($nextPayments) > 1) {

            /**
             * @param PaymentInterface $payment
             * @return void
             */
            $callback = function ($payment) use (&$nearestPayment) {
                if ($payment != $nearestPayment) {
                    $baseTm = $this->coreDate->gmtTimestamp($nearestPayment->getScheduledAt());
                    $currentTm = $this->coreDate->gmtTimestamp($payment->getScheduledAt());
                    if ($currentTm < $baseTm) {
                        $nearestPayment = $payment;
                    }
                }
            };
            array_walk($nextPayments, $callback);
        }
        return $nearestPayment;
    }
}
