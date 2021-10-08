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
 * @version    2.15.3
 * @copyright  Copyright (c) 2021 Aheadworks Inc. (https://aheadworks.com/)
 * @license    https://aheadworks.com/end-user-license-agreement/
 */
namespace Aheadworks\Sarp2\Model\Payment\Token;

use Aheadworks\Sarp2\Api\Data\PaymentTokenInterface;
use Aheadworks\Sarp2\Api\Data\PaymentTokenSearchResultsInterface;
use Aheadworks\Sarp2\Api\PaymentTokenRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\SortOrderBuilder;

/**
 * Class Finder
 * @package Aheadworks\Sarp2\Model\Payment\Token
 */
class Finder
{
    /**
     * @var PaymentTokenRepositoryInterface
     */
    private $tokenRepository;

    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;

    /**
     * @var SortOrderBuilder
     */
    private $sortOrderBuilder;

    /**
     * @param PaymentTokenRepositoryInterface $tokenRepository
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param SortOrderBuilder $sortOrderBuilder
     */
    public function __construct(
        PaymentTokenRepositoryInterface $tokenRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        SortOrderBuilder $sortOrderBuilder
    ) {
        $this->tokenRepository = $tokenRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->sortOrderBuilder = $sortOrderBuilder;
    }

    /**
     * Find existing payment token using token instance
     *
     * @param PaymentTokenInterface $token
     * @return PaymentTokenInterface|null
     */
    public function findExisting(PaymentTokenInterface $token)
    {
        $this->sortOrderBuilder
            ->setField(PaymentTokenInterface::CREATED_AT)
            ->setDescendingDirection();
        $this->searchCriteriaBuilder
            ->addFilter(PaymentTokenInterface::PAYMENT_METHOD, $token->getPaymentMethod())
            ->addFilter(PaymentTokenInterface::TYPE, $token->getType())
            ->addFilter(PaymentTokenInterface::TOKEN_VALUE, $token->getTokenValue())
            ->addFilter(PaymentTokenInterface::IS_ACTIVE, true)
            ->addSortOrder($this->sortOrderBuilder->create());

        $searchResult = $this->tokenRepository->getList($this->searchCriteriaBuilder->create());
        if ($searchResult->getTotalCount() > 0) {
            $items = $searchResult->getItems();
            return reset($items);
        }
        return null;
    }

    /**
     * Find existing payment tokens using token ids
     *
     * @param int[] $ids
     * @param string|null $paymentMethod
     * @return PaymentTokenInterface[]
     */
    public function findExistingByIds($ids, $paymentMethod = null)
    {
        $this->searchCriteriaBuilder
            ->addFilter(PaymentTokenInterface::IS_ACTIVE, true)
            ->addFilter(PaymentTokenInterface::TOKEN_ID, $ids, 'in');

        if ($paymentMethod) {
            $this->searchCriteriaBuilder
                ->addFilter(PaymentTokenInterface::PAYMENT_METHOD, $paymentMethod);
        }

        /** @var PaymentTokenSearchResultsInterface $searchResult */
        $searchResult = $this->tokenRepository->getList($this->searchCriteriaBuilder->create());

        return $searchResult->getItems();
    }

    /**
     * Find existing payment token using token value
     *
     * @param string $tokenValue
     * @return PaymentTokenInterface|null
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function findExistingByValue($tokenValue)
    {
        $this->sortOrderBuilder
            ->setField(PaymentTokenInterface::CREATED_AT)
            ->setDescendingDirection();
        $this->searchCriteriaBuilder
            ->addFilter(PaymentTokenInterface::TOKEN_VALUE, $tokenValue)
            ->addFilter(PaymentTokenInterface::IS_ACTIVE, true)
            ->addSortOrder($this->sortOrderBuilder->create());

        $searchResult = $this->tokenRepository->getList($this->searchCriteriaBuilder->create());
        if ($searchResult->getTotalCount() > 0) {
            $items = $searchResult->getItems();
            return reset($items);
        }
        return null;
    }
}
