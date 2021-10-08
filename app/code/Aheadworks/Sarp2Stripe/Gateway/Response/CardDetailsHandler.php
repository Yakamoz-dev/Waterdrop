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

use Aheadworks\Sarp2Stripe\Gateway\SubjectReader;
use Aheadworks\Sarp2Stripe\Model\Adapter\StripePayments\CreditCard;
use Aheadworks\Sarp2Stripe\Model\Adapter\StripePayments\CreditCardResolver;
use Aheadworks\Sarp2Stripe\Model\Adapter\StripePayments\Response;
use Magento\Payment\Gateway\Response\HandlerInterface;
use Magento\Sales\Model\Order\Payment;

/**
 * Class CardDetailsHandler
 * @package Aheadworks\Sarp2Stripe\Gateway\Response
 */
class CardDetailsHandler implements HandlerInterface
{
    /**
     * @var SubjectReader
     */
    private $subjectReader;

    /**
     * @var CreditCardResolver
     */
    private $creditCardResolver;

    /**
     * @param SubjectReader $subjectReader
     * @param CreditCardResolver $creditCardResolver
     */
    public function __construct(
        SubjectReader $subjectReader,
        CreditCardResolver $creditCardResolver
    ) {
        $this->subjectReader = $subjectReader;
        $this->creditCardResolver = $creditCardResolver;
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

        /** @var CreditCard|null $creditCard */
        $creditCard = $this->creditCardResolver->getByResponse($response);
        if ($creditCard) {
            $payment->setCcType($creditCard->getType());
            $payment->setCcLast4($creditCard->getLast4());
            $payment->setCcExpMonth($creditCard->getExpMonth());
            $payment->setCcExpYear($creditCard->getExpYear());
        }
    }
}
