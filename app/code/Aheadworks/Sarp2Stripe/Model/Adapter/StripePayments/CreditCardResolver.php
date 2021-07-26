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
namespace Aheadworks\Sarp2Stripe\Model\Adapter\StripePayments;

use Aheadworks\Sarp2Stripe\Model\Adapter\StripePayments\StripeObject\Converter as StripeObjectConverter;
use Aheadworks\Sarp2Stripe\PaymentData\PaymentIntent\CreditCardType;
use Stripe\PaymentIntent;

/**
 * Class CreditCardResolver
 * @package Aheadworks\Sarp2Stripe\Model\Adapter\StripePayments
 */
class CreditCardResolver
{
    /**
     * @var StripeObjectConverter
     */
    private $stripeObjectConverter;

    /**
     * @var CreditCardFactory
     */
    private $creaditCardFactory;

    /**
     * @var CreditCardType
     */
    private $creditCardType;

    /**
     * @param StripeObjectConverter $stripeObjectConverter
     * @param CreditCardFactory $creaditCardFactory
     * @param CreditCardType $creditCardType
     */
    public function __construct(
        StripeObjectConverter $stripeObjectConverter,
        CreditCardFactory $creaditCardFactory,
        CreditCardType $creditCardType
    ) {
        $this->stripeObjectConverter = $stripeObjectConverter;
        $this->creaditCardFactory = $creaditCardFactory;
        $this->creditCardType = $creditCardType;
    }

    /**
     * Get credit card by payment item
     *
     * @param PaymentIntent $paymentIntent
     * @return CreditCard
     */
    public function getByPaymentIntent($paymentIntent)
    {
        $response = $this->stripeObjectConverter->toResponse($paymentIntent);

        return $this->getByResponse($response);
    }

    /**
     * Get credit card by payment response
     *
     * @param Response $response
     * @return CreditCard|null
     */
    public function getByResponse($response)
    {
        $creditCard = null;

        $charges = $response->getData('charges');
        if ($charges && isset($charges['data'][0])) {
            $charge = $charges['data'][0];
            $paymentMethodDetails = $charge['payment_method_details'];
            $typeData = $paymentMethodDetails['type'];
            $creditCardData = $paymentMethodDetails[$typeData];

            $creditCard = $this->getCreditCard($creditCardData);
        }

        return $creditCard;
    }

    /**
     * Get credit card
     *
     * @param array $creditCardData
     * @return CreditCard
     */
    private function getCreditCard($creditCardData)
    {
        $cardType = isset($creditCardData['brand']) ? $this->creditCardType->getPrepared($creditCardData['brand']) : '';
        $cardData = [
            CreditCard::TYPE => $cardType,
            CreditCard::LAST4 => isset($creditCardData['last4']) ? $creditCardData['last4'] : '',
            CreditCard::EXP_MONTH => isset($creditCardData['exp_month']) ? $creditCardData['exp_month'] : '',
            CreditCard::EXP_YEAR => isset($creditCardData['exp_year']) ? $creditCardData['exp_year'] : ''
        ];

        /** @var CreditCard $creditCard */
        $creditCard = $this->creaditCardFactory->create(['data' => $cardData]);

        return $creditCard;
    }
}
