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
namespace Aheadworks\Sarp2\Model\Payment\Sampler\Gateway\Nmi\Response;

use Magento\Payment\Gateway\Response\HandlerInterface;
use Aheadworks\Sarp2\Model\Payment\Sampler\Gateway\SubjectReader;
use Aheadworks\Sarp2\Gateway\Nmi\SubjectReaderFactory as NmiSubjectReaderFactory;

/**
 * Class TransactionIdHandler
 *
 * @package Aheadworks\Sarp2\Model\Payment\Sampler\Gateway\BamboraApac\Response
 */
class TransactionIdHandler implements HandlerInterface
{
    /**
     * @var SubjectReader
     */
    private $subjectReader;

    /**
     * @var NmiSubjectReaderFactory
     */
    private $nmiSubjectReaderFactory;

    /**
     * @param SubjectReader $subjectReader
     * @param NmiSubjectReaderFactory $nmiSubjectReaderFactory
     */
    public function __construct(
        SubjectReader $subjectReader,
        NmiSubjectReaderFactory $nmiSubjectReaderFactory
    ) {
        $this->subjectReader = $subjectReader;
        $this->nmiSubjectReaderFactory = $nmiSubjectReaderFactory;
    }

    /**
     * Handles response
     *
     * @param array $handlingSubject
     * @param array $response
     * @return void
     */
    public function handle(array $handlingSubject, array $response)
    {
        $paymentDO = $this->subjectReader->readPayment($handlingSubject);
        $command = $this->subjectReader->readCommand($handlingSubject);
        $payment = $paymentDO->getPayment();

        $nmiSubjectReader = $this->nmiSubjectReaderFactory->getInstance();
        if ($nmiSubjectReader) {
            $transactionResponse = $nmiSubjectReader->readTransactionResponse($response);
            $transactionId = $transactionResponse->getTransactionId();

            $payment->setLastTransactionId($transactionId);
            $payment->setAdditionalInformation($command . '_txn_id', $transactionId);
        }
    }
}
