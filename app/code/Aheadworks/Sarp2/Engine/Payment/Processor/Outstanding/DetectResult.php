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
namespace Aheadworks\Sarp2\Engine\Payment\Processor\Outstanding;

use Aheadworks\Sarp2\Engine\Payment;
use Aheadworks\Sarp2\Engine\PaymentInterface;

/**
 * Class DetectResult
 * @package Aheadworks\Sarp2\Engine\Payment\Processor\Outstanding
 */
class DetectResult
{
    /**
     * @var PaymentInterface[]
     */
    private $todayPayments;

    /**
     * @var PaymentInterface[]
     */
    private $outstandingPayments;

    /**
     * @param array $todayPayments
     * @param array $outstandingPayments
     */
    public function __construct(
        array $todayPayments,
        array $outstandingPayments = []
    ) {
        $this->todayPayments = $todayPayments;
        $this->outstandingPayments = $outstandingPayments;
    }

    /**
     * Get today payments
     *
     * @return PaymentInterface[]
     */
    public function getTodayPayments()
    {
        return $this->todayPayments;
    }

    /**
     * Get outstanding payments
     *
     * @return PaymentInterface[]|Payment[]
     */
    public function getOutstandingPayments()
    {
        return $this->outstandingPayments;
    }
}
