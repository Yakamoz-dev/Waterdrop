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
 * @package    Sarp2
 * @version    2.15.0
 * @copyright  Copyright (c) 2021 Aheadworks Inc. (https://aheadworks.com/)
 * @license    https://aheadworks.com/end-user-license-agreement/
 */
namespace Aheadworks\Sarp2\Engine\Profile\Action\Type\ChangePaymentInformation\Validator;

use Aheadworks\Sarp2\Engine\Payment\PaymentsList;
use Aheadworks\Sarp2\Engine\PaymentInterface;
use Aheadworks\Sarp2\Engine\Profile\Action\Validation\AbstractValidator;

/**
 * Class PaymentType
 * @package Aheadworks\Sarp2\Engine\Profile\Action\Type\ChangePaymentInformation\Validator
 */
class PaymentType extends AbstractValidator
{
    /**
     * @var PaymentsList
     */
    private $paymentsList;

    /**
     * @param PaymentsList $paymentsList
     */
    public function __construct(
        PaymentsList $paymentsList
    ) {
        $this->paymentsList = $paymentsList;
    }

    /**
     * @inheritDoc
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    protected function performValidation($profile, $action)
    {
        $payments = $this->paymentsList->getLastScheduled($profile->getProfileId());
        foreach ($payments as $payment) {
            if ($payment->getType() == PaymentInterface::TYPE_LAST_PERIOD_HOLDER) {
                $this->addMessages(['Payment details cannot be changed after all payments are done.']);
                break;
            }
        }
    }
}
