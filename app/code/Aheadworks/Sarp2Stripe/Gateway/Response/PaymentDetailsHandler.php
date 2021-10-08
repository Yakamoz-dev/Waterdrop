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
namespace Aheadworks\Sarp2Stripe\Gateway\Response;

use Aheadworks\Sarp2\Model\Payment\Method\Data\DataAssignerInterface;
use Aheadworks\Sarp2Stripe\Gateway\SubjectReader;
use Aheadworks\Sarp2Stripe\Model\Adapter\StripePayments\Response;
use Magento\Payment\Gateway\Response\HandlerInterface;
use Magento\Sales\Model\Order\Payment;

/**
 * Class PaymentDetailsHandler
 * @package Aheadworks\Sarp2Stripe\Gateway\Response
 */
class PaymentDetailsHandler implements HandlerInterface
{
    /**
     * @var SubjectReader
     */
    private $subjectReader;

    /**
     * @param SubjectReader $subjectReader
     */
    public function __construct(SubjectReader $subjectReader)
    {
        $this->subjectReader = $subjectReader;
    }

    /**
     * Handles fraud messages
     *
     * @param array $handlingSubject
     * @param array $response
     * @return void
     */
    public function handle(array $handlingSubject, array $response)
    {
        $paymentDO = $this->subjectReader->readPayment($handlingSubject);

        /** @var Payment $payment */
        $payment = $paymentDO->getPayment();
        /** @var Response $response */
        $response = $this->subjectReader->readTransactionResponse($response);

        $payment->setCcTransId($response->getId());
        $payment->setLastTransId($response->getId());
        $payment->setIsTransactionClosed(0);
        $payment->setIsFraudDetected(false);

        $payment->unsAdditionalInformation(DataAssignerInterface::PAYMENT_TOKEN_ID);
    }
}
