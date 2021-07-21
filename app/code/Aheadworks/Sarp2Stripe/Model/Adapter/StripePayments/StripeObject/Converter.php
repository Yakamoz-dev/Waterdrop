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
namespace Aheadworks\Sarp2Stripe\Model\Adapter\StripePayments\StripeObject;

use Aheadworks\Sarp2Stripe\Model\Adapter\StripePayments\Response;
use Aheadworks\Sarp2Stripe\Model\Adapter\StripePayments\ResponseFactory;
use Stripe\PaymentIntent;

/**
 * Class Converter
 * @package Aheadworks\Sarp2Stripe\Model\Adapter\StripePayments\StripeObject
 */
class Converter
{
    /**
     * @var ResponseFactory
     */
    private $responseFactory;

    /**
     * @param ResponseFactory $responseFactory
     */
    public function __construct(
        ResponseFactory $responseFactory
    ) {
        $this->responseFactory = $responseFactory;
    }

    /**
     * Convert payment intent to response
     *
     * @param PaymentIntent $paymentIntent
     * @return Response
     */
    public function toResponse($paymentIntent)
    {
        $responseData = $paymentIntent->jsonSerialize();
        $response = $this->responseFactory->create(['data' => $responseData]);

        return $response;
    }
}
