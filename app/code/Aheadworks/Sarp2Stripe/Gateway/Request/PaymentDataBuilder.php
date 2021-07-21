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
 * @version    1.0.5
 * @copyright  Copyright (c) 2021 Aheadworks Inc. (https://aheadworks.com/)
 * @license    https://aheadworks.com/end-user-license-agreement/
 */
namespace Aheadworks\Sarp2Stripe\Gateway\Request;

use Aheadworks\Sarp2\Api\PaymentTokenRepositoryInterface;
use Aheadworks\Sarp2\Model\Payment\Method\Data\DataAssignerInterface;
use Aheadworks\Sarp2Stripe\Gateway\Config\Config as StripeConfig;
use Aheadworks\Sarp2Stripe\Gateway\SubjectReader;
use Magento\Payment\Gateway\Request\BuilderInterface;
use Magento\Sales\Model\Order\Payment;

/**
 * Class PaymentDataBuilder
 * @package Aheadworks\Sarp2Stripe\Gateway\Request
 */
class PaymentDataBuilder implements BuilderInterface
{
    /**#@+
     * Payment data
     */
    const AMOUNT = 'amount';
    const CURRENCY = 'currency';
    const DESCRIPTION = 'description';
    const METADATA = 'metadata';
    const PAYMENT_METHOD = 'payment_method';
    const CUSTOMER = 'customer';
    /**#@-*/

    /**
     * @var StripeConfig
     */
    private $stripeConfig;

    /**
     * @var SubjectReader
     */
    private $subjectReader;

    /**
     * @var PaymentTokenRepositoryInterface
     */
    private $paymentTokenRepository;

    /**
     * @param StripeConfig $stripeConfig
     * @param SubjectReader $subjectReader
     * @param PaymentTokenRepositoryInterface $paymentTokenRepository
     */
    public function __construct(
        StripeConfig $stripeConfig,
        SubjectReader $subjectReader,
        PaymentTokenRepositoryInterface $paymentTokenRepository
    ) {
        $this->stripeConfig = $stripeConfig;
        $this->subjectReader = $subjectReader;
        $this->paymentTokenRepository = $paymentTokenRepository;
    }

    /**
     * @inheritdoc
     */
    public function build(array $buildSubject)
    {
        $paymentDO = $this->subjectReader->readPayment($buildSubject);

        /** @var Payment $payment */
        $payment = $paymentDO->getPayment();
        $order = $payment->getOrder();

        $params = $this->stripeConfig->getStripeParamsFromOrder($order);

        $result = [
            self::AMOUNT => $params['amount'],
            self::CURRENCY => $params['currency'],
            self::DESCRIPTION => $params['description'],
            self::METADATA => $params['metadata'],
        ];

        $paymentTokenId = $payment->getAdditionalInformation(DataAssignerInterface::PAYMENT_TOKEN_ID);
        if (!$paymentTokenId) {
            throw new \LogicException('Payment token Id does not specified.');
        }

        $token = $this->paymentTokenRepository->get($paymentTokenId);
        $gatewayToken = $token->getTokenValue();
        $customerToken = $token->getDetails('customerToken');

        $result[self::PAYMENT_METHOD] = $gatewayToken;
        $result[self::CUSTOMER] = $customerToken;

        return $result;
    }
}
