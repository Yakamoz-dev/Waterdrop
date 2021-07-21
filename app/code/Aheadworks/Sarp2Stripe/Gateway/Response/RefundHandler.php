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
namespace Aheadworks\Sarp2Stripe\Gateway\Response;

/**
 * Class RefundHandler
 * @package Aheadworks\Sarp2Stripe\Gateway\Response
 */
class RefundHandler extends TransactionIdHandler
{
    /**
     * {@inheritdoc}
     */
    protected function setTransactionId($payment, $transactionResponse)
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    protected function shouldCloseTransaction()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    protected function shouldCloseParentTransaction($payment)
    {
        return !(bool)$payment->getCreditmemo()->getInvoice()->canRefund();
    }
}
