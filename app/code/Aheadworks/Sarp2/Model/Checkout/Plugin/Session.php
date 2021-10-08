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
namespace Aheadworks\Sarp2\Model\Checkout\Plugin;

use Aheadworks\Sarp2\Model\Quote\Checker\HasSubscriptions;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Quote\Model\Quote;

/**
 * Class Session
 * @package Aheadworks\Sarp2\Model\Checkout\Plugin
 */
class Session
{
    /**
     * @var CartRepositoryInterface
     */
    private $quoteRepository;

    /**
     * @var HasSubscriptions
     */
    private $quoteChecker;

    /**
     * @param CartRepositoryInterface $quoteRepository
     * @param HasSubscriptions $quoteChecker
     */
    public function __construct(
        CartRepositoryInterface $quoteRepository,
        HasSubscriptions $quoteChecker
    ) {
        $this->quoteRepository = $quoteRepository;
        $this->quoteChecker = $quoteChecker;
    }

    /**
     * @param CheckoutSession $subject
     * @param \Closure $proceed
     * @return CheckoutSession
     */
    public function aroundClearQuote(CheckoutSession $subject, \Closure $proceed)
    {
        $quoteId = $subject->getLastSuccessQuoteId();

        $proceed();
        if ($quoteId) {
            /** @var Quote $quote */
            $quote = $this->quoteRepository->get($quoteId);
            if ($this->quoteChecker->check($quote)) {
                $subject->setLastSuccessProfileIds(null);
            }
        }

        return $subject;
    }
}
