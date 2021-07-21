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
namespace Aheadworks\Sarp2Stripe\Plugin\Model;

use Aheadworks\Sarp2\Model\Payment\Method\Data\DataAssignerInterface;
use Aheadworks\Sarp2Stripe\Gateway\TokenAssigner;
use Magento\Quote\Model\Quote;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\Data\OrderPaymentInterface;
use StripeIntegration\Payments\Model\PaymentIntent;
use Stripe\PaymentIntent as StripePaymentIntent;

/**
 * Class PaymentIntentPlugin
 *
 * @package Aheadworks\Sarp2Stripe\Plugin\Model
 */
class PaymentIntentPlugin
{
    /**
     * @var TokenAssigner
     */
    private $tokenAssigner;

    /**
     * @param TokenAssigner $tokenAssigner
     */
    public function __construct(
        TokenAssigner $tokenAssigner
    ) {
        $this->tokenAssigner = $tokenAssigner;
    }

    /**
     * Assign token to payment
     *
     * @param PaymentIntent $subject
     * @param StripePaymentIntent $result
     * @param OrderInterface $order
     * @return StripePaymentIntent
     * @throws \Magento\Framework\Exception\LocalizedException
     * @SuppressWarnings(PMD.UnusedFormalParameter)
     */
    public function afterConfirmAndAssociateWithOrder($subject, $result, $order)
    {
        /** @var OrderPaymentInterface|null $payment */
        $payment = $order->getPayment();

        if ($payment
            && $payment->getAdditionalInformation(DataAssignerInterface::IS_SARP_TOKEN_ENABLED)
        ) {
            $this->tokenAssigner->assignByPaymentIntent($payment, $result);
        }

        return $result;
    }

    /**
     * Set off-session mode
     *
     * @param PaymentIntent $subject
     * @param array $result
     * @param Quote $quote
     * @return array
     */
    public function afterGetPaymentMethodDetails($subject, $result, $quote)
    {
        if ($quote
            && $quote->getPayment()->getAdditionalInformation(DataAssignerInterface::IS_SARP_TOKEN_ENABLED)) {
            $result['setup_future_usage'] = "off_session";
        }

        return $result;
    }
}
