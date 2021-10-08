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
namespace Aheadworks\Sarp2\PaymentData;

use Magento\Payment\Model\InfoInterface;
use Magento\Quote\Model\Quote;

/**
 * Class Payment
 * @package Aheadworks\Sarp2\PaymentData
 */
class Payment implements PaymentInterface
{
    /**
     * @var InfoInterface
     */
    private $paymentInfo;

    /**
     * @var Quote
     */
    private $quote;

    /**
     * @param InfoInterface $paymentInfo
     * @param Quote $quote
     */
    public function __construct(
        InfoInterface $paymentInfo,
        Quote $quote
    ) {
        $this->paymentInfo = $paymentInfo;
        $this->quote = $quote;
    }

    /**
     * {@inheritdoc}
     */
    public function getPaymentInfo()
    {
        return $this->paymentInfo;
    }

    /**
     * {@inheritdoc}
     */
    public function getQuote()
    {
        return $this->quote;
    }
}
