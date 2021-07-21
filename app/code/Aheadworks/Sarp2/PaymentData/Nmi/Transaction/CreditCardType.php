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
namespace Aheadworks\Sarp2\PaymentData\Nmi\Transaction;

use Magento\Payment\Gateway\ConfigInterface;

/**
 * Class CreditCardType
 *
 * @package Aheadworks\Sarp2\PaymentData\Nmi\Transaction
 */
class CreditCardType
{
    /**
     * @var ConfigInterface
     */
    private $nmiGatewayConfig;

    /**
     * @param ConfigInterface $gatewayConfig
     */
    public function __construct(
        ConfigInterface $gatewayConfig
    ) {
        $this->nmiGatewayConfig = $gatewayConfig;
    }

    /**
     * Get prepared credit card type
     *
     * @param \Aheadworks\Nmi\Model\Api\Result\Response $transactionResponse
     * @return string
     */
    public function getPrepared($transactionResponse)
    {
        $creditCardType = $transactionResponse->getCardType();
        $replacedCreditCardType = str_replace(' ', '-', strtolower($creditCardType));
        $mapper = $this->nmiGatewayConfig->getCctypesMapper();
        return isset($mapper[$replacedCreditCardType]) ? $mapper[$replacedCreditCardType] : '';
    }
}
