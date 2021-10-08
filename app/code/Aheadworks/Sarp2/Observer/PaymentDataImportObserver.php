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
namespace Aheadworks\Sarp2\Observer;

use Aheadworks\Sarp2\Model\Payment\Method\Data\DataAssignerInterface;
use Aheadworks\Sarp2\Model\Quote\Checker\HasSubscriptions;
use Magento\Framework\DataObject;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Quote\Model\Quote\Payment;

/**
 * Class PaymentDataImportObserver
 * @package Aheadworks\Sarp2\Observer
 */
class PaymentDataImportObserver implements ObserverInterface
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
        /* @var $payment Payment */
        $payment = $event->getData('payment');
        /** @var DataObject $input */
        $input = $event->getData('input');
        $quote = $payment->getQuote();
        $additionalData = $input->getAdditionalData() ? : [];
        $additionalData[DataAssignerInterface::IS_SARP_TOKEN_ENABLED] = $this->quoteChecker->check($quote);
        $input->setAdditionalData($additionalData);
    }
}
