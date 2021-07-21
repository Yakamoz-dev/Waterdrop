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
namespace Aheadworks\Sarp2Stripe\PaymentData\PaymentIntent;

use Aheadworks\Sarp2Stripe\Gateway\Config\Config as StripeConfig;

/**
 * Class CreditCardType
 * @package Aheadworks\Sarp2Stripe\PaymentData\PaymentIntent
 */
class CreditCardType
{
    /**
     * @var StripeConfig
     */
    private $stripeConfig;

    /**
     * @param StripeConfig $stripeConfig
     */
    public function __construct(
        StripeConfig $stripeConfig
    ) {
        $this->stripeConfig = $stripeConfig;
    }

    /**
     * Get prepared credit card type
     *
     * @param string $typeCode
     * @return string
     */
    public function getPrepared($typeCode)
    {
        $map = $this->stripeConfig->getCcTypesMap();
        return isset($map[$typeCode]) ? $map[$typeCode] : '';
    }
}
