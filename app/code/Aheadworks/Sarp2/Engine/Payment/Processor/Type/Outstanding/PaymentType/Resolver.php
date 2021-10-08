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
namespace Aheadworks\Sarp2\Engine\Payment\Processor\Type\Outstanding\PaymentType;

use Aheadworks\Sarp2\Engine\PaymentInterface;

/**
 * Class Resolver
 * @package Aheadworks\Sarp2\Engine\Payment\Processor\Type\Outstanding\PaymentType
 */
class Resolver
{
    /**
     * @var array
     */
    private $map = [
        PaymentInterface::STATUS_PLANNED => PaymentInterface::TYPE_PLANNED,
        PaymentInterface::STATUS_PENDING => PaymentInterface::TYPE_ACTUAL,
        PaymentInterface::STATUS_RETRYING => PaymentInterface::TYPE_REATTEMPT
    ];

    /**
     * Get recovered payment type
     *
     * @param PaymentInterface $payment
     * @return string|null
     */
    public function getPaymentType($payment)
    {
        $status = $payment->getPaymentStatus();
        return isset($this->map[$status])
            ? $this->map[$status]
            : null;
    }
}
