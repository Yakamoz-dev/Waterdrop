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
namespace Aheadworks\Sarp2\Observer;

use Aheadworks\Sarp2\Model\Quote\Checker\HasSubscriptions;
use Magento\Framework\DataObject;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Payment\Model\MethodInterface;
use Magento\Payment\Model\Method\Free;
use Magento\Quote\Model\Quote;

/**
 * Class PaymentMethodAvailabilityObserver
 * @package Aheadworks\Sarp2\Observer
 */
class PaymentMethodAvailabilityObserver implements ObserverInterface
{
    /**
     * @var HasSubscriptions
     */
    private $quoteChecker;

    /**
     * @param HasSubscriptions $quoteChecker
     */
    public function __construct(HasSubscriptions $quoteChecker)
    {
        $this->quoteChecker = $quoteChecker;
    }

    /**
     * {@inheritdoc}
     */
    public function execute(Observer $observer)
    {
        $event = $observer->getEvent();
        /** @var DataObject $result */
        $result = $event->getResult();
        /** @var Quote $quote */
        $quote = $event->getQuote();

        if ($quote) {
            /** @var MethodInterface $methodInstance */
            $methodInstance = $event->getMethodInstance();

            $canUseMixedCheckout = (bool) $methodInstance->getConfigData('aw_sarp_can_use_mixed_checkout');
            $canUseSubscriptionCheckout = (bool) $methodInstance->getConfigData(
                'aw_sarp_can_use_subscription_checkout'
            );

            if ($quote
                && (!$canUseMixedCheckout && $this->quoteChecker->checkHasBoth($quote)
                    || !$canUseSubscriptionCheckout && $this->quoteChecker->checkHasSubscriptionsOnly($quote))
                || ($quote->hasData('aw_sarp_get_recurring_payments_flag') && !$canUseSubscriptionCheckout)
            ) {
                $result->setData('is_available', false);
            }

            if ($quote->getGrandTotal() <= 0
                && $quote->hasData('aw_sarp_allow_free_payment_method')
                && $methodInstance->getCode() == Free::PAYMENT_METHOD_FREE_CODE
            ) {
                 $result->setData('is_available', true);
            }
        }

        return $this;
    }
}
