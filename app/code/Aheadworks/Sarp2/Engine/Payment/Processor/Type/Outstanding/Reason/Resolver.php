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
namespace Aheadworks\Sarp2\Engine\Payment\Processor\Type\Outstanding\Reason;

use Aheadworks\Sarp2\Engine\PaymentInterface;

/**
 * Class Resolver
 * @package Aheadworks\Sarp2\Engine\Payment\Processor\Type\Outstanding\Reason
 */
class Resolver
{
    /**
     * Outstanding reasons
     */
    const REASON_REACTIVATED = 1;
    const REASON_CYCLE_MISSING = 2;

    /**
     * Get outstanding reason
     *
     * @param PaymentInterface $payment
     * @return string
     */
    public function getReason($payment)
    {
        return $payment->getSchedule()->isReactivated()
            ? self::REASON_REACTIVATED
            : self::REASON_CYCLE_MISSING;
    }
}
