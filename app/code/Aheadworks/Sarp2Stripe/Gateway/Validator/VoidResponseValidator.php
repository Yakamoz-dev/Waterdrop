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
namespace Aheadworks\Sarp2Stripe\Gateway\Validator;

use Aheadworks\Sarp2Stripe\Gateway\SubjectReader;
use Aheadworks\Sarp2Stripe\Model\Adapter\StripePayments\Response;
use Magento\Payment\Gateway\Validator\AbstractValidator;
use Magento\Payment\Gateway\Validator\ResultInterface;
use Magento\Payment\Gateway\Validator\ResultInterfaceFactory;

/**
 * Class VoidResponseValidator
 * @package Aheadworks\Sarp2Stripe\Gateway\Validator
 */
class VoidResponseValidator extends AbstractValidator
{
    /**
     * @var SubjectReader
     */
    private $subjectReader;

    /**
     * @param ResultInterfaceFactory $resultFactory
     * @param SubjectReader $subjectReader
     */
    public function __construct(
        ResultInterfaceFactory $resultFactory,
        SubjectReader $subjectReader
    ) {
        parent::__construct($resultFactory);
        $this->subjectReader = $subjectReader;
    }

    /**
     * Performs validation of result status
     *
     * @param array $validationSubject
     * @return ResultInterface
     */
    public function validate(array $validationSubject)
    {
        /** @var Response $response */
        $response = $this->subjectReader->readResponseObject($validationSubject);

        $isValid = false;
        $errorMessages = [];

        if ($response->getStatus() == 'canceled') {
            $isValid = true;
        }

        return $this->createResult($isValid, $errorMessages);
    }
}
