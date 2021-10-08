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
namespace Aheadworks\Sarp2\Engine\Payment\Checker;

use Aheadworks\Sarp2\Engine\PaymentInterface;
use Aheadworks\Sarp2\Model\Profile\Source\Status;

/**
 * Class IsProcessable
 * @package Aheadworks\Sarp2\Engine\Payment\Checker
 */
class IsProcessable
{
    /**
     * @var array
     */
    private $typeToStatusesMap = [
        PaymentInterface::TYPE_PLANNED => [PaymentInterface::STATUS_PLANNED],
        PaymentInterface::TYPE_LAST_PERIOD_HOLDER => [PaymentInterface::STATUS_PLANNED],
        PaymentInterface::TYPE_ACTUAL => [
            PaymentInterface::STATUS_PENDING
        ],
        PaymentInterface::TYPE_REATTEMPT => [
            PaymentInterface::STATUS_PENDING,
            PaymentInterface::STATUS_RETRYING
        ],
        PaymentInterface::TYPE_OUTSTANDING => [
            PaymentInterface::STATUS_PLANNED,
            PaymentInterface::STATUS_PENDING,
            PaymentInterface::STATUS_RETRYING
        ]
    ];

    /**
     * @var array
     */
    private $typeToProfileStatusesRestrictedMap = [
        PaymentInterface::TYPE_PLANNED => [
            Status::CANCELLED,
            Status::EXPIRED,
            Status::SUSPENDED
        ],
        PaymentInterface::TYPE_ACTUAL => [
            Status::CANCELLED,
            Status::EXPIRED,
            Status::SUSPENDED
        ],
        PaymentInterface::TYPE_REATTEMPT => [
            Status::CANCELLED,
            Status::EXPIRED
        ]
    ];

    /**
     * Check if payment is processable
     *
     * @param PaymentInterface $payment
     * @param string $paymentType
     * @return bool
     */
    public function check(PaymentInterface $payment, $paymentType)
    {
        $availablePaymentStatuses = $this->getAvailablePaymentStatuses($paymentType);
        if (!in_array($payment->getPaymentStatus(), $availablePaymentStatuses)) {
            return false;
        }

        $disallowedProfileStatuses = isset($this->typeToProfileStatusesRestrictedMap[$paymentType])
            ? $this->typeToProfileStatusesRestrictedMap[$paymentType]
            : [];
        if (in_array($payment->getProfile()->getStatus(), $disallowedProfileStatuses)) {
            return false;
        }

        return true;
    }

    /**
     * Get available payment statuses
     *
     * @param string $paymentType
     * @return array
     */
    public function getAvailablePaymentStatuses($paymentType)
    {
        return isset($this->typeToStatusesMap[$paymentType])
            ? $this->typeToStatusesMap[$paymentType]
            : [];
    }
}
