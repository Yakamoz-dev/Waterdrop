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
namespace Aheadworks\Sarp2\Model\Payment\Method\Data;

use Aheadworks\Sarp2\Api\PaymentTokenRepositoryInterface;
use Magento\Payment\Model\InfoInterface;

/**
 * Class DefaultDataAssigner
 *
 * @package Aheadworks\Sarp2\Model\Payment\Method\Data
 */
class DefaultDataAssigner implements DataAssignerInterface
{
    /**
     * @var PaymentTokenRepositoryInterface
     */
    private $tokenRepository;

    /**
     * @param PaymentTokenRepositoryInterface $tokenRepository
     */
    public function __construct(PaymentTokenRepositoryInterface $tokenRepository)
    {
        $this->tokenRepository = $tokenRepository;
    }

    /**
     * @inheritDoc
     */
    public function assignDataToBaseMethod(InfoInterface $paymentInfo, array $additionalData)
    {
        if (isset($additionalData[self::IS_SARP_TOKEN_ENABLED])) {
            $paymentInfo->setAdditionalInformation(
                self::IS_SARP_TOKEN_ENABLED,
                $additionalData[self::IS_SARP_TOKEN_ENABLED]
            );
        }
    }

    /**
     * @inheritDoc
     */
    public function assignDataToRecurringMethod(InfoInterface $paymentInfo, array $additionalData)
    {
        if (isset($additionalData[self::TOKEN_ID])) {
            $paymentInfo->setAdditionalInformation(
                self::GATEWAY_TOKEN,
                $this->tokenRepository->get($additionalData[self::TOKEN_ID])
            );
        }
    }
}
