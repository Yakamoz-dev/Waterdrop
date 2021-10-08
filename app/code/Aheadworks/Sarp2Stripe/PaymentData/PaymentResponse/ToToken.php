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
namespace Aheadworks\Sarp2Stripe\PaymentData\PaymentResponse;

use Aheadworks\Sarp2\Api\Data\PaymentTokenInterface;
use Aheadworks\Sarp2\Api\Data\PaymentTokenInterfaceFactory;
use Aheadworks\Sarp2Stripe\Model\Adapter\StripePayments\CreditCard;
use Aheadworks\Sarp2Stripe\Model\Adapter\StripePayments\CreditCardResolver;
use Aheadworks\Sarp2\Model\Payment\Token;
use Aheadworks\Sarp2Stripe\Model\Adapter\StripePayments\Response as StripePaymentResponse;
use Aheadworks\Sarp2Stripe\PaymentData\ExpirationDate;

/**
 * Class ToToken
 *
 * @package Aheadworks\Sarp2Stripe\PaymentData\PaymentResponse
 */
class ToToken
{
    /**
     * @var PaymentTokenInterfaceFactory
     */
    private $tokenFactory;

    /**
     * @var CreditCardResolver
     */
    private $creditCardResolver;

    /**
     * @var ExpirationDate
     */
    private $expirationDate;

    /**
     * @param PaymentTokenInterfaceFactory $tokenFactory
     * @param CreditCardResolver $creditCardResolver
     * @param ExpirationDate $expirationDate
     */
    public function __construct(
        PaymentTokenInterfaceFactory $tokenFactory,
        CreditCardResolver $creditCardResolver,
        ExpirationDate $expirationDate
    ) {
        $this->tokenFactory = $tokenFactory;
        $this->creditCardResolver = $creditCardResolver;
        $this->expirationDate = $expirationDate;
    }

    /**
     * Convert Stripe payment response into payment token
     *
     * @param StripePaymentResponse $paymentResponse
     * @return PaymentTokenInterface
     */
    public function convert($paymentResponse)
    {
        /** @var PaymentTokenInterface $paymentToken */
        $paymentToken = $this->tokenFactory->create();

        $token = isset($paymentResponse['payment_method']) ? $paymentResponse['payment_method'] : '';

        $paymentToken->setPaymentMethod('stripe_payments')
            ->setType(Token::TOKEN_TYPE_CARD)
            ->setTokenValue($token)
            ->setDetails('customerToken', isset($paymentResponse['customer']) ? $paymentResponse['customer'] : null);

        /** @var CreditCard|null $creditCard */
        $creditCard = $this->creditCardResolver->getByResponse($paymentResponse);
        if ($creditCard) {
            $paymentToken
                ->setDetails('typeCode', $creditCard->getType())
                ->setDetails('lastCcNumber', $creditCard->getLast4())
                ->setDetails('expirationDate', $creditCard->getExpMonth() . '/' . $creditCard->getExpYear())
                ->setExpiresAt(
                    $this->expirationDate->getFormatted($creditCard->getExpMonth(), $creditCard->getExpYear())
                );
        }

        return $paymentToken;
    }
}
