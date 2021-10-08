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
namespace Aheadworks\Sarp2Stripe\Gateway\Command;

use Aheadworks\Sarp2Stripe\Gateway\SubjectReader;
use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Payment\Gateway\Command\CommandPoolInterface;
use Magento\Payment\Gateway\CommandInterface;
use Magento\Payment\Gateway\Data\OrderAdapterInterface;
use Magento\Payment\Gateway\Data\PaymentDataObjectInterface;
use Magento\Payment\Gateway\Helper\ContextHelper;
use Magento\Sales\Api\Data\OrderPaymentInterface;
use Magento\Sales\Api\Data\TransactionInterface;
use Magento\Sales\Api\TransactionRepositoryInterface;

/**
 * Class CaptureStrategyCommand
 *
 * @package Aheadworks\Sarp2Stripe\Gateway\Command
 */
class CaptureStrategyCommand implements CommandInterface
{
    /**
     * Stripe authorize and capture command
     */
    const SALE = 'sale';

    /**
     * Stripe capture command
     */
    const CAPTURE = 'settlement';

    /**
     * Stripe vault capture command
     */
    const VAULT_CAPTURE = 'vault_capture';

    /**
     * @var CommandPoolInterface
     */
    private $commandPool;

    /**
     * @var TransactionRepositoryInterface
     */
    private $transactionRepository;

    /**
     * @var FilterBuilder
     */
    private $filterBuilder;

    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;

    /**
     * @var SubjectReader
     */
    private $subjectReader;

    /**
     * Constructor
     *
     * @param CommandPoolInterface $commandPool
     * @param TransactionRepositoryInterface $repository
     * @param FilterBuilder $filterBuilder
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param SubjectReader $subjectReader
     */
    public function __construct(
        CommandPoolInterface $commandPool,
        TransactionRepositoryInterface $repository,
        FilterBuilder $filterBuilder,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        SubjectReader $subjectReader
    ) {
        $this->commandPool = $commandPool;
        $this->transactionRepository = $repository;
        $this->filterBuilder = $filterBuilder;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->subjectReader = $subjectReader;
    }

    /**
     * @inheritdoc
     */
    public function execute(array $commandSubject)
    {
        $paymentDO = $this->subjectReader->readPayment($commandSubject);

        $command = $this->getCommand($paymentDO);
        $this->commandPool->get($command)->execute($commandSubject);
    }

    /**
     * Get execution command name.
     *
     * @param PaymentDataObjectInterface $paymentDO
     * @return string
     */
    private function getCommand(PaymentDataObjectInterface $paymentDO)
    {
        $payment = $paymentDO->getPayment();
        ContextHelper::assertOrderPayment($payment);

        // if auth transaction does not exist then execute authorize&capture command
        $existsCapture = $this->isExistsCaptureTransaction($payment);
        if (!$payment->getAuthorizationTransaction() && !$existsCapture) {
            return self::SALE;
        }

        // do capture for authorization transaction
        if (!$existsCapture && !$this->isExpiredAuthorization($payment, $paymentDO->getOrder())) {
            return self::CAPTURE;
        }

        // process capture for payment via Vault
        return self::VAULT_CAPTURE;
    }

    /**
     * Checks if authorization transaction does not expired yet.
     *
     * @param OrderPaymentInterface $payment
     * @param OrderAdapterInterface $orderAdapter
     * @return bool
     */
    private function isExpiredAuthorization(OrderPaymentInterface $payment, OrderAdapterInterface $orderAdapter)
    {
        return false;
    }

    /**
     * Check if capture transaction already exists
     *
     * @param OrderPaymentInterface $payment
     * @return bool
     */
    private function isExistsCaptureTransaction(OrderPaymentInterface $payment)
    {
        $this->searchCriteriaBuilder->addFilters(
            [
                $this->filterBuilder
                    ->setField('payment_id')
                    ->setValue($payment->getId())
                    ->create(),
            ]
        );

        $this->searchCriteriaBuilder->addFilters(
            [
                $this->filterBuilder
                    ->setField('txn_type')
                    ->setValue(TransactionInterface::TYPE_CAPTURE)
                    ->create(),
            ]
        );

        $searchCriteria = $this->searchCriteriaBuilder->create();

        $count = $this->transactionRepository->getList($searchCriteria)->getTotalCount();
        return (boolean) $count;
    }
}
