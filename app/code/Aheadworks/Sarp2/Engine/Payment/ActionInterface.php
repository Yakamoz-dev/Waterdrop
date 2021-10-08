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
namespace Aheadworks\Sarp2\Engine\Payment;

use Aheadworks\Sarp2\Engine\Exception\ScheduledPaymentException;
use Aheadworks\Sarp2\Engine\PaymentInterface;
use Aheadworks\Sarp2\Engine\Payment\Action\ResultInterface;

/**
 * Interface ActionInterface
 * @package Aheadworks\Sarp2\Engine\Payment
 */
interface ActionInterface
{
    /**
     * Payment action types
     */
    const TYPE_SINGLE = 'single';
    const TYPE_BUNDLED = 'bundled';

    /**
     * Perform pay action
     *
     * @param PaymentInterface $payment
     * @return ResultInterface
     * @throws ScheduledPaymentException
     */
    public function pay(PaymentInterface $payment);
}
