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
 * @package    Sarp2Stripe
 * @version    1.0.6
 * @copyright  Copyright (c) 2021 Aheadworks Inc. (https://aheadworks.com/)
 * @license    https://aheadworks.com/end-user-license-agreement/
 */
namespace Aheadworks\Sarp2Stripe\Sampler\Gateway\Request;

use Magento\Payment\Gateway\Request\BuilderInterface;
use Aheadworks\Sarp2\Model\Payment\Sampler\Gateway\SubjectReader;
use Aheadworks\Sarp2Stripe\Sampler\Gateway\CustomerId as StripeSamplerCustomerId;

/**
 * Class PaymentDataBuilder
 *
 * @package Aheadworks\Sarp2Stripe\Sampler\Gateway\Request
 */
class PaymentDataBuilder implements BuilderInterface
{
    /**#@+
     * Payment data
     */
    const AMOUNT = 'amount';
    const CURRENCY = 'currency';
    const PAYMENT_METHOD = 'payment_method';
    const CUSTOMER = 'customer';
    const SAVE_PAYMENT_METHOD = 'save_payment_method';
    const SETUP_FUTURE_USAGE = 'setup_future_usage';
    /**#@-*/

    /**
     * @var SubjectReader
     */
    private $subjectReader;

    /**
     * @var StripeSamplerCustomerId
     */
    private $stripeSamplerCustomerId;

    /**
     * @param SubjectReader $subjectReader
     * @param StripeSamplerCustomerId $stripeSamplerCustomerId
     */
    public function __construct(
        SubjectReader $subjectReader,
        StripeSamplerCustomerId $stripeSamplerCustomerId
    ) {
        $this->subjectReader = $subjectReader;
        $this->stripeSamplerCustomerId = $stripeSamplerCustomerId;
    }

    /**
     * {@inheritdoc}
     */
    public function build(array $buildSubject)
    {
        $paymentDO = $this->subjectReader->readPayment($buildSubject);

        $payment = $paymentDO->getPayment();

        $integerAmount = $payment->getAmount() * 100;
        $paymentToken = $payment->getAdditionalInformation('token');

        $customerId = $payment->getAdditionalInformation('customer_id');
        if (empty($customerId)) {
            $customerId = $this->stripeSamplerCustomerId->resolve($payment->getProfile());
        }

        $result = [
            self::AMOUNT => $integerAmount,
            self::CURRENCY => $payment->getCurrencyCode(),
            self::PAYMENT_METHOD => $paymentToken,
            self::SAVE_PAYMENT_METHOD => 'true',
            self::SETUP_FUTURE_USAGE => 'off_session'
        ];

        if (!empty($customerId)) {
            $result[self::CUSTOMER] = $customerId;
        }

        return $result;
    }
}
