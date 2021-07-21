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
namespace Aheadworks\Sarp2\Engine\Profile\Action\Type\SetPaymentToken\Validator;

use Aheadworks\Sarp2\Api\PaymentTokenRepositoryInterface;
use Aheadworks\Sarp2\Engine\Profile\Action\Validation\AbstractValidator;
use Magento\Framework\Exception\LocalizedException;

/**
 * Class PaymentToken
 * @package Aheadworks\Sarp2\Engine\Profile\Action\Type\SetPaymentToken\Validator
 */
class PaymentToken extends AbstractValidator
{
    /**
     * @var PaymentTokenRepositoryInterface
     */
    private $paymentTokenRepository;

    /**
     * @param PaymentTokenRepositoryInterface $paymentTokenRepository
     */
    public function __construct(
        PaymentTokenRepositoryInterface $paymentTokenRepository
    ) {
        $this->paymentTokenRepository = $paymentTokenRepository;
    }

    /**
     * @inheritDoc
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    protected function performValidation($profile, $action)
    {
        $tokenId = $action->getData()->getTokenId();

        try {
            $this->paymentTokenRepository->get($tokenId);
        } catch (LocalizedException $exception) {
            $this->addMessages(['Payment token is not isset.']);
        }
    }
}
