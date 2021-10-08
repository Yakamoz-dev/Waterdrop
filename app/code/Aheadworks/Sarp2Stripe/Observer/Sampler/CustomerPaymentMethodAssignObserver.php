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
namespace Aheadworks\Sarp2Stripe\Observer\Sampler;

use Magento\Framework\Event;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Aheadworks\Sarp2Stripe\Model\Adapter\StripePayments as StripePaymentsAdapter;
use Aheadworks\Sarp2\Model\Payment\SamplerInfoInterface;
use Aheadworks\Sarp2Stripe\Sampler\Gateway\CustomerId as StripeSamplerCustomerId;

/**
 * Class CustomerPaymentMethodAssignObserver
 *
 * @package Aheadworks\Sarp2Stripe\Observer\Sampler
 */
class CustomerPaymentMethodAssignObserver implements ObserverInterface
{
    /**
     * @var StripeSamplerCustomerId
     */
    private $stripeSamplerCustomerId;

    /**
     * @var StripePaymentsAdapter
     */
    private $stripePaymentsAdapter;

    /**
     * @param StripeSamplerCustomerId $stripeSamplerCustomerId
     * @param StripePaymentsAdapter $stripePaymentsAdapter
     */
    public function __construct(
        StripeSamplerCustomerId $stripeSamplerCustomerId,
        StripePaymentsAdapter $stripePaymentsAdapter
    ) {
        $this->stripeSamplerCustomerId = $stripeSamplerCustomerId;
        $this->stripePaymentsAdapter = $stripePaymentsAdapter;
    }

    /**
     * {@inheritdoc}
     */
    public function execute(Observer $observer)
    {
        /** @var Event $event */
        $event = $observer->getEvent();
        /** @var SamplerInfoInterface $payment */
        $payment = $event->getData('payment');
        if ($payment) {
            $paymentToken = $payment->getAdditionalInformation('token');
            $customerId = $this->stripeSamplerCustomerId->resolve($payment->getProfile());
            $payment->setAdditionalInformation('customer_id', $customerId);
            $this->stripePaymentsAdapter->attachPaymentMethodToCustomerIfNotAttached($customerId, $paymentToken);
        }

        return $this;
    }
}
