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
namespace Aheadworks\Sarp2\Engine\Payment\Evaluation;

/**
 * Class PaymentDetails
 * @package Aheadworks\Sarp2\Engine\Payment\Evaluation
 */
class PaymentDetails
{
    /**
     * @var string
     */
    private $paymentPeriod;

    /**
     * @var string
     */
    private $paymentType;

    /**
     * @var string
     */
    private $date;

    /**
     * @var float
     */
    private $totalAmount;

    /**
     * @var float
     */
    private $baseTotalAmount;

    /**
     * @param string $paymentPeriod
     * @param string $paymentType
     * @param string $date
     * @param float $totalAmount
     * @param float $baseTotalAmount
     */
    public function __construct(
        $paymentPeriod,
        $paymentType,
        $date,
        $totalAmount,
        $baseTotalAmount
    ) {
        $this->paymentPeriod = $paymentPeriod;
        $this->paymentType = $paymentType;
        $this->date = $date;
        $this->totalAmount = $totalAmount;
        $this->baseTotalAmount = $baseTotalAmount;
    }

    /**
     * Get payment period
     *
     * @return string
     */
    public function getPaymentPeriod()
    {
        return $this->paymentPeriod;
    }

    /**
     * Get payment type
     *
     * @return string
     */
    public function getPaymentType()
    {
        return $this->paymentType;
    }

    /**
     * Get payment date
     *
     * @return string
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Get total amount in profile currency
     *
     * @return float
     */
    public function getTotalAmount()
    {
        return $this->totalAmount;
    }

    /**
     * Get total amount in base currency
     *
     * @return float
     */
    public function getBaseTotalAmount()
    {
        return $this->baseTotalAmount;
    }
}
