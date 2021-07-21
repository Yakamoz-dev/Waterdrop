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
namespace Aheadworks\Sarp2\Model\Checkout\ConfigProvider;

use Aheadworks\Sarp2\Model\Quote\Checker\HasSubscriptions;
use Magento\Checkout\Model\ConfigProviderInterface;
use Magento\Checkout\Model\Session;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Quote\Api\Data\CartInterface;
use Magento\Quote\Model\Quote;

/**
 * Class ConfigProvider
 * @package Aheadworks\Sarp2\Model\Checkout
 */
class MixedQuote implements ConfigProviderInterface
{
    /**
     * @var Session
     */
    private $checkoutSession;

    /**
     * @var CartRepositoryInterface
     */
    private $quoteRepository;

    /**
     * @var HasSubscriptions
     */
    private $quoteChecker;

    /**
     * @param Session $checkoutSession
     * @param CartRepositoryInterface $quoteRepository
     * @param HasSubscriptions $quoteChecker
     */
    public function __construct(
        Session $checkoutSession,
        CartRepositoryInterface $quoteRepository,
        HasSubscriptions $quoteChecker
    ) {
        $this->checkoutSession = $checkoutSession;
        $this->quoteRepository = $quoteRepository;
        $this->quoteChecker = $quoteChecker;
    }

    /**
     * {@inheritdoc}
     */
    public function getConfig()
    {
        $config = [];
        $quoteId = $this->checkoutSession->getQuote()->getId();
        if ($quoteId) {
            try {
                /** @var CartInterface|Quote $quote */
                $quote = $this->quoteRepository->getActive($quoteId);
                $config['isAwSarp2QuoteSubscription'] = $this->quoteChecker->checkHasSubscriptionsOnly(
                    $quote
                );
                $config['isAwSarp2QuoteMixed'] = $this->quoteChecker->checkHasBoth($quote);
            } catch (NoSuchEntityException $e) {
            }
        }
        return $config;
    }
}
