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
namespace Aheadworks\Sarp2Stripe\Sampler\Gateway;

use Aheadworks\Sarp2\Api\Data\PaymentTokenInterface;
use Aheadworks\Sarp2\Api\PaymentTokenRepositoryInterface;
use Aheadworks\Sarp2\Model\Payment\Token\Finder;
use Aheadworks\Sarp2Stripe\Model\Adapter\StripePayments\Response as StripePaymentResponse;
use Aheadworks\Sarp2Stripe\PaymentData\PaymentResponse\ToToken as PaymentResponseToToken;
use Magento\Payment\Model\InfoInterface;

/**
 * Class TokenAssigner
 *
 * @package Aheadworks\Sarp2Stripe\Sampler\Gateway
 */
class TokenAssigner
{
    /**
     * @var Finder
     */
    private $tokenFinder;

    /**
     * @var PaymentTokenRepositoryInterface
     */
    private $tokenRepository;

    /**
     * @var PaymentResponseToToken
     */
    private $paymentResponseToTokenConverter;

    /**
     * @param Finder $tokenFinder
     * @param PaymentTokenRepositoryInterface $tokenRepository
     * @param PaymentResponseToToken $paymentResponseToTokenConverter
     */
    public function __construct(
        Finder $tokenFinder,
        PaymentTokenRepositoryInterface $tokenRepository,
        PaymentResponseToToken $paymentResponseToTokenConverter
    ) {
        $this->tokenFinder = $tokenFinder;
        $this->tokenRepository = $tokenRepository;
        $this->paymentResponseToTokenConverter = $paymentResponseToTokenConverter;
    }

    /**
     * Assign payment token using Stripe response data
     *
     * @param InfoInterface $payment
     * @param StripePaymentResponse $paymentResponse
     * @return InfoInterface
     */
    public function assignByPaymentResponse($payment, $paymentResponse)
    {
        $token = $this->getTokenToAssign(
            $this->paymentResponseToTokenConverter->convert($paymentResponse)
        );
        $payment->setAdditionalInformation('aw_sarp_payment_token_id', $token->getTokenId());

        return $payment;
    }

    /**
     * Get token to assign
     *
     * @param PaymentTokenInterface $candidate
     * @return PaymentTokenInterface
     */
    private function getTokenToAssign($candidate)
    {
        $existing = $this->tokenFinder->findExisting($candidate);
        if (!$existing) {
            $candidate->setIsActive(true);
            $this->tokenRepository->save($candidate);
            return $candidate;
        }
        return $existing;
    }
}
