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
namespace Aheadworks\Sarp2\Gateway\BamboraApac\Response;

use Aheadworks\Sarp2\Gateway\BamboraApac\TokenAssigner;
use Magento\Payment\Gateway\Response\HandlerInterface;
use Magento\Sales\Model\Order\Payment;
use Aheadworks\Sarp2\Gateway\BamboraApac\SubjectReaderFactory;

/**
 * Class VaultDetailsHandler
 *
 * @package Aheadworks\Sarp2\Gateway\BamboraApac\Response
 */
class VaultDetailsHandler implements HandlerInterface
{
    /**
     * @var SubjectReaderFactory
     */
    private $subjectReaderFactory;

    /**
     * @var TokenAssigner
     */
    private $tokenAssigner;

    /**
     * @param TokenAssigner $tokenAssigner
     * @param SubjectReaderFactory $subjectReaderFactory
     */
    public function __construct(
        TokenAssigner $tokenAssigner,
        SubjectReaderFactory $subjectReaderFactory
    ) {
        $this->tokenAssigner = $tokenAssigner;
        $this->subjectReaderFactory = $subjectReaderFactory;
    }

    /**
     * @inheritdoc
     *
     * @throws \Exception
     */
    public function handle(array $handlingSubject, array $response)
    {
        $subjectReader = $this->subjectReaderFactory->getInstance();
        if (!$subjectReader) {
            return null;
        }
        $paymentDO = $subjectReader->readPayment($handlingSubject);
        /** @var Payment $payment */
        $payment = $paymentDO->getPayment();

        $isPaymentTokenEnabled = $payment->getAdditionalInformation('is_aw_sarp_payment_token_enabled');
        if ($isPaymentTokenEnabled) {
            $transaction = $subjectReader->readTransactionResponse($response);
            $this->tokenAssigner->assignByTransaction($payment, $transaction);
        }
    }
}
