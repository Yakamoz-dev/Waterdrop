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
namespace Aheadworks\Sarp2\Engine\Payment\Generator;

use Aheadworks\Sarp2\Api\Data\ProfileInterface;
use Aheadworks\Sarp2\Engine\DataResolver\NextPaymentDate;
use Aheadworks\Sarp2\Engine\Payment\Evaluation\PaymentDetails;
use Aheadworks\Sarp2\Engine\Payment\Evaluation\PaymentDetailsFactory;
use Aheadworks\Sarp2\Engine\Payment\Schedule\Checker;
use Aheadworks\Sarp2\Engine\Payment\Schedule\ValueResolver;
use Aheadworks\Sarp2\Engine\Payment\ScheduleInterface;
use Aheadworks\Sarp2\Engine\PaymentInterface;
use Magento\Framework\Stdlib\DateTime\DateTime as CoreDate;

/**
 * Class Evaluation
 * @package Aheadworks\Sarp2\Engine\Payment\Generator
 */
class Evaluation
{
    /**
     * @var CoreDate
     */
    private $coreDate;

    /**
     * @var NextPaymentDate
     */
    private $nextPaymentDate;

    /**
     * @var PaymentDetailsFactory
     */
    private $detailsFactory;

    /**
     * @var Checker
     */
    private $scheduleChecker;

    /**
     * @var ValueResolver
     */
    private $schedulePeriodValueResolver;

    /**
     * @param CoreDate $coreDate
     * @param NextPaymentDate $nextPaymentDate
     * @param PaymentDetailsFactory $detailsFactory
     * @param Checker $scheduleChecker
     * @param ValueResolver $periodValueResolver
     */
    public function __construct(
        CoreDate $coreDate,
        NextPaymentDate $nextPaymentDate,
        PaymentDetailsFactory $detailsFactory,
        Checker $scheduleChecker,
        ValueResolver $periodValueResolver
    ) {
        $this->coreDate = $coreDate;
        $this->nextPaymentDate = $nextPaymentDate;
        $this->detailsFactory = $detailsFactory;
        $this->scheduleChecker = $scheduleChecker;
        $this->schedulePeriodValueResolver = $periodValueResolver;
    }

    /**
     * Evaluate possible payment details for current date.
     * Assumed that current date is a payment date candidate.
     * Returns an empty array if there is no possible payments
     *
     * @param ScheduleInterface $schedule
     * @param ProfileInterface $profile
     * @param string $currentDate
     * @param string|null $lastPaymentDate
     * @return PaymentDetails[]
     */
    public function evaluate(
        ScheduleInterface $schedule,
        ProfileInterface $profile,
        $currentDate,
        $lastPaymentDate = null
    ) {
        $details = null;

        $wasPayments = $lastPaymentDate !== null;
        $baseDate = $wasPayments
            ? $lastPaymentDate
            : $profile->getStartDate();

        $baseTm = $this->getGmtTimestampExclTime($baseDate);
        $currentTm = $this->getGmtTimestampExclTime($currentDate);

        $estimateTypes = $wasPayments
            ? $currentTm >= $this->getGmtTimestampExclTime(
                $this->nextPaymentDate->getDateNext(
                    $lastPaymentDate,
                    $this->schedulePeriodValueResolver->getPeriod($schedule),
                    $this->schedulePeriodValueResolver->getFrequency($schedule)
                )
            )
            : true;

        if ($estimateTypes) {
            if ($profile->getProfileDefinition()->getIsInitialFeeEnabled() && !$schedule->isInitialPaid()) {
                $details = $this->detailsFactory->create(
                    [
                        'paymentPeriod' => PaymentInterface::PERIOD_INITIAL,
                        'paymentType' => PaymentInterface::TYPE_PLANNED,
                        'date' => $profile->getStartDate(),
                        'totalAmount' => $profile->getInitialGrandTotal(),
                        'baseTotalAmount' => $profile->getBaseInitialGrandTotal()
                    ]
                );
            } elseif ($baseTm <= $currentTm || !$wasPayments) {
                if ($this->scheduleChecker->isTrialNextPayment($schedule)) {
                    $details = $this->detailsFactory->create(
                        [
                            'paymentPeriod' => PaymentInterface::PERIOD_TRIAL,
                            'paymentType' => PaymentInterface::TYPE_PLANNED,
                            'date' => $currentDate,
                            'totalAmount' => $profile->getTrialGrandTotal(),
                            'baseTotalAmount' => $profile->getBaseTrialGrandTotal()
                        ]
                    );
                } else {
                    $totalRegularCounts = $schedule->getRegularTotalCount();

                    if ($schedule->isMembershipModel()
                        && $this->scheduleChecker->isFiniteSubscription($schedule)
                        && $this->scheduleChecker->isMembershipNextPayment($schedule)
                    ) {
                        $details = $this->detailsFactory->create(
                            [
                                'paymentPeriod' => PaymentInterface::PERIOD_REGULAR,
                                'paymentType' => PaymentInterface::TYPE_LAST_PERIOD_HOLDER,
                                'date' => $currentDate,
                                'totalAmount' => 0,
                                'baseTotalAmount' => 0
                            ]
                        );
                    } elseif (!$this->scheduleChecker->isFiniteSubscription($schedule)
                             || $schedule->getRegularCount() < $totalRegularCounts
                    ) {
                        $details = $this->detailsFactory->create(
                            [
                                'paymentPeriod' => PaymentInterface::PERIOD_REGULAR,
                                'paymentType' => PaymentInterface::TYPE_PLANNED,
                                'date' => $currentDate,
                                'totalAmount' => $profile->getRegularGrandTotal(),
                                'baseTotalAmount' => $profile->getBaseRegularGrandTotal()
                            ]
                        );
                    }
                }
            }
        }

        return $details ? [$details] : [];
    }

    /**
     * Get GMT timestamp without time
     *
     * @param string $date
     * @return int
     */
    private function getGmtTimestampExclTime($date)
    {
        $dateTime = (new \DateTime($date))
            ->setTime(0, 0, 0);
        return $this->coreDate->gmtTimestamp($dateTime);
    }
}
