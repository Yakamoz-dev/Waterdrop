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
namespace Aheadworks\Sarp2\PaymentData\BamboraApac\Transaction;

use Magento\Payment\Gateway\ConfigInterface;

/**
 * Class CreditCardType
 *
 * @package Aheadworks\Sarp2\PaymentData\BamboraApac\Transaction
 */
class CreditCardType
{
    /**
     * @var ConfigInterface
     */
    private $bamboraApacGatewayConfig;

    /**
     * @param ConfigInterface $bamboraApacGatewayConfig
     */
    public function __construct(
        ConfigInterface $bamboraApacGatewayConfig
    ) {
        $this->bamboraApacGatewayConfig = $bamboraApacGatewayConfig;
    }

    /**
     * Get prepared credit card type
     *
     * @param \Aheadworks\BamboraApac\Model\Api\Result\Response $transactionResponse
     * @return string
     */
    public function getPrepared($transactionResponse)
    {
        $creditCardType = $transactionResponse->getCardType();
        $replacedCreditCardType = str_replace(' ', '-', strtolower($creditCardType));
        $mapper = $this->bamboraApacGatewayConfig->getCctypesMapper();
        return isset($mapper[$replacedCreditCardType]) ? $mapper[$replacedCreditCardType] : '';
    }
}
