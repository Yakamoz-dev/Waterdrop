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
namespace Aheadworks\Sarp2Stripe\Sampler\Gateway\Response;

use Magento\Payment\Gateway\Response\HandlerInterface;
use Aheadworks\Sarp2\Model\Payment\Sampler\Gateway\SubjectReader;
use Aheadworks\Sarp2Stripe\Gateway\SubjectReader as StripePaymentsSubjectReader;
use Aheadworks\Sarp2Stripe\Model\Adapter\StripePayments\Response;
use Aheadworks\Sarp2\Model\Payment\SamplerInfoInterface;
use Aheadworks\Sarp2Stripe\Sampler\Gateway\TokenAssigner
    as StripeSamplerTokenAssigner;

/**
 * Class PaymentDetailsHandler
 *
 * @package Aheadworks\Sarp2Stripe\Sampler\Gateway\Response
 */
class PaymentDetailsHandler implements HandlerInterface
{
    /**
     * @var SubjectReader
     */
    private $subjectReader;

    /**
     * @var StripePaymentsSubjectReader
     */
    private $stripePaymentsSubjectReader;

    /**
     * @var StripeSamplerTokenAssigner
     */
    private $stripeSamplerTokenAssigner;

    /**
     * @param SubjectReader $subjectReader
     * @param StripePaymentsSubjectReader $stripePaymentsSubjectReader
     * @param StripeSamplerTokenAssigner $stripeSamplerTokenAssigner
     */
    public function __construct(
        SubjectReader $subjectReader,
        StripePaymentsSubjectReader $stripePaymentsSubjectReader,
        StripeSamplerTokenAssigner $stripeSamplerTokenAssigner
    ) {
        $this->subjectReader = $subjectReader;
        $this->stripePaymentsSubjectReader = $stripePaymentsSubjectReader;
        $this->stripeSamplerTokenAssigner = $stripeSamplerTokenAssigner;
    }

    /**
     * Handles response
     *
     * @param array $handlingSubject
     * @param array $response
     * @return void
     */
    public function handle(array $handlingSubject, array $response)
    {
        $paymentDO = $this->subjectReader->readPayment($handlingSubject);

        /** @var SamplerInfoInterface $payment */
        $payment = $paymentDO->getPayment();
        /** @var Response $transactionResponse */
        $transactionResponse = $this->stripePaymentsSubjectReader->readTransactionResponse($response);

        $this->stripeSamplerTokenAssigner->assignByPaymentResponse($payment, $transactionResponse);
    }
}
