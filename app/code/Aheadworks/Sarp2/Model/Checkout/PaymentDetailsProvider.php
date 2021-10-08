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
namespace Aheadworks\Sarp2\Model\Checkout;

use Magento\Checkout\Model\PaymentDetailsFactory;
use Magento\Payment\Model\MethodList;
use Magento\Quote\Model\QuoteFactory;
use Magento\Quote\Model\Quote;

/**
 * Class PaymentDetailsProvider
 *
 * @package Aheadworks\Sarp2\Model\Checkout
 */
class PaymentDetailsProvider
{
    /**
     * @var PaymentDetailsFactory
     */
    private $paymentDetailsFactory;

    /**
     * @var MethodList
     */
    private $methodList;

    /**
     * @var QuoteFactory
     */
    private $quoteFactory;

    /**
     * @param PaymentDetailsFactory $paymentDetailsFactory
     * @param MethodList $methodList
     * @param QuoteFactory $quoteFactory
     */
    public function __construct(
        PaymentDetailsFactory $paymentDetailsFactory,
        MethodList $methodList,
        QuoteFactory $quoteFactory
    ) {
        $this->paymentDetailsFactory = $paymentDetailsFactory;
        $this->methodList = $methodList;
        $this->quoteFactory = $quoteFactory;
    }

    /**
     * @inheritdoc
     */
    public function getPaymentInformation()
    {
        /** @var PaymentDetailsInterface $paymentDetails */
        $paymentDetails = $this->paymentDetailsFactory->create();

        /** @var Quote $quote */
        $quote = $this->quoteFactory->create();
        $quote->setData('aw_sarp_get_recurring_payments_flag', true);
        $methodList = $this->methodList->getAvailableMethods($quote);
        foreach ($methodList as $method) {
            $method->getInfoInstance()->setQuote($quote);
        }
        $paymentDetails->setPaymentMethods($methodList);
        return $paymentDetails;
    }
}
