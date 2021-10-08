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
namespace Aheadworks\Sarp2Stripe\Gateway;

use Aheadworks\Sarp2\Api\PaymentTokenRepositoryInterface;
use Aheadworks\Sarp2\Gateway\AbstractTokenAssigner;
use Aheadworks\Sarp2\Model\Payment\Token\Finder;
use Aheadworks\Sarp2Stripe\PaymentData\PaymentIntent\ToToken as PaymentIntentToToken;
use Magento\Sales\Api\Data\OrderPaymentInterface;
use Stripe\PaymentIntent as StripePaymentIntent;

/**
 * Class TokenAssigner
 * @package Aheadworks\Sarp2Stripe\Gateway
 */
class TokenAssigner extends AbstractTokenAssigner
{
    /**
     * @var PaymentIntentToToken
     */
    private $paymentIntentToTokenConverter;

    /**
     * @param Finder $tokenFinder
     * @param PaymentTokenRepositoryInterface $tokenRepository
     * @param PaymentIntentToToken $paymentIntentToTokenConverter
     */
    public function __construct(
        Finder $tokenFinder,
        PaymentTokenRepositoryInterface $tokenRepository,
        PaymentIntentToToken $paymentIntentToTokenConverter
    ) {
        parent::__construct($tokenFinder, $tokenRepository);
        $this->paymentIntentToTokenConverter = $paymentIntentToTokenConverter;
    }

    /**
     * Assign payment token using credit card details
     *
     * @param OrderPaymentInterface $payment
     * @param StripePaymentIntent $paymentIntent
     * @return OrderPaymentInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function assignBypaymentIntent($payment, $paymentIntent)
    {
        $token = $this->paymentIntentToTokenConverter->convert($paymentIntent);
        $this->saveNewOrUpdateToken($token);
        $payment->setAdditionalInformation(AbstractTokenAssigner::SARP_PAYMENT_TOKEN_ID, $token->getTokenId());
        return $payment;
    }
}
