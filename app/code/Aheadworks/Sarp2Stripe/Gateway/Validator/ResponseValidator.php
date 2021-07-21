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
namespace Aheadworks\Sarp2Stripe\Gateway\Validator;

use Aheadworks\Sarp2\Model\Payment\Sampler\Exception\ExceptionWithUnmaskedMessage;
use Aheadworks\Sarp2Stripe\Gateway\SubjectReader;
use Aheadworks\Sarp2Stripe\Model\Adapter\StripePayments\Response;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Payment\Gateway\Validator\AbstractValidator;
use Magento\Payment\Gateway\Validator\ResultInterface;
use Magento\Payment\Gateway\Validator\ResultInterfaceFactory;
use StripeIntegration\Payments\Model\PaymentIntent;

/**
 * Class ResponseCodeValidator
 * @package Aheadworks\Sarp2Stripe\Gateway\Validator
 */
class ResponseValidator extends AbstractValidator
{
    /**
     * @var SubjectReader
     */
    private $subjectReader;

    /**
     * @var CheckoutSession
     */
    private $session;

    /**
     * @param ResultInterfaceFactory $resultFactory
     * @param SubjectReader $subjectReader
     * @param CheckoutSession $session
     */
    public function __construct(
        ResultInterfaceFactory $resultFactory,
        SubjectReader $subjectReader,
        CheckoutSession $session
    ) {
        parent::__construct($resultFactory);
        $this->subjectReader = $subjectReader;
        $this->session = $session;
    }

    /**
     * Performs validation of result status
     *
     * @param array $validationSubject
     * @return ResultInterface
     * @throws ExceptionWithUnmaskedMessage
     */
    public function validate(array $validationSubject)
    {
        /** @var Response $response */
        $response = $this->subjectReader->readResponseObject($validationSubject);

        $isValid = false;
        $errorMessages = [];

        if ($response->getStatus() == PaymentIntent::CAPTURED
            || $response->getStatus() == PaymentIntent::AUTHORIZED
        ) {
            $isValid = true;
        } elseif ($response->getStatus() == PaymentIntent::REQUIRES_ACTION) {
            $piSecrets = $response->getData('client_secret');
            $paymentIntentId = $response->getId();
            if ($paymentIntentId && $piSecrets) {
                $this->session->setCachedStripePaymentIntent($paymentIntentId);
                // Front-end checkout case, this will trigger the 3DS modal.
                throw new ExceptionWithUnmaskedMessage(__("Authentication Required: " . $piSecrets));
            }
        }

        return $this->createResult($isValid, $errorMessages);
    }
}
