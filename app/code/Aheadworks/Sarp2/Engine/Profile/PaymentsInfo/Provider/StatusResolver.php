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
namespace Aheadworks\Sarp2\Engine\Profile\PaymentsInfo\Provider;

use Aheadworks\Sarp2\Api\Data\ScheduledPaymentInfoInterface;
use Aheadworks\Sarp2\Engine\PaymentInterface;

/**
 * Class StatusResolver
 * @package Aheadworks\Sarp2\Engine\Profile\PaymentsInfo\Provider
 */
class StatusResolver
{
    /**
     * @var array
     */
    private $typeToInfoStatusMap = [
        PaymentInterface::TYPE_PLANNED => ScheduledPaymentInfoInterface::PAYMENT_STATUS_SCHEDULED,
        PaymentInterface::TYPE_ACTUAL => ScheduledPaymentInfoInterface::PAYMENT_STATUS_SCHEDULED,
        PaymentInterface::TYPE_REATTEMPT => ScheduledPaymentInfoInterface::PAYMENT_STATUS_REATTEMPT,
        PaymentInterface::TYPE_OUTSTANDING => ScheduledPaymentInfoInterface::PAYMENT_STATUS_SCHEDULED,
        PaymentInterface::TYPE_LAST_PERIOD_HOLDER => ScheduledPaymentInfoInterface::PAYMENT_STATUS_LAST_PERIOD_HOLDER
    ];

    /**
     * Get info status corresponding to payment instance
     *
     * @param PaymentInterface $payment
     * @return string
     */
    public function getInfoStatus($payment)
    {
        $paymentType = $payment->getType();
        return isset($this->typeToInfoStatusMap[$paymentType])
            ? $this->typeToInfoStatusMap[$paymentType]
            : ScheduledPaymentInfoInterface::PAYMENT_STATUS_NO_PAYMENT;
    }
}
