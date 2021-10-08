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
namespace Aheadworks\Sarp2\Observer\OneOff;

use Aheadworks\Sarp2\Api\Data\ProfileItemInterface;
use Aheadworks\Sarp2\Model\Profile\Item\Checker\IsOneOffItem;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Quote\Model\Quote\Item as QuoteItem;

/**
 * Class ConvertQuoteItemToProfileItemObserver
 * @package Aheadworks\Sarp2\Observer\OneOff
 */
class CopyPrice implements ObserverInterface
{
    /**
     * @var IsOneOffItem
     */
    private $isOneOffItem;

    /**
     * CopyPrice constructor.
     * @param IsOneOffItem $isOneOffItem
     */
    public function __construct(
        IsOneOffItem $isOneOffItem
    ) {
        $this->isOneOffItem = $isOneOffItem;
    }

    /**
     * {@inheritdoc}
     */
    public function execute(Observer $observer)
    {
        $event = $observer->getEvent();
        /** @var QuoteItem $quoteItem */
        $quoteItem = $event->getData('quote_item');
        /** @var ProfileItemInterface $profileItem */
        $profileItem = $event->getData('profile_item');

        if ($this->isOneOffItem->check($profileItem)) {
            $profileItem
                ->setRegularPrice($quoteItem->getPrice())
                ->setBaseRegularPrice($quoteItem->getBasePrice())
                ->setRegularPriceInclTax($quoteItem->getPriceInclTax())
                ->setBaseRegularPriceInclTax($quoteItem->getBasePriceInclTax())
                ->setRegularRowTotal($quoteItem->getRowTotal())
                ->setBaseRegularRowTotal($quoteItem->getBaseRowTotal())
                ->setRegularRowTotalInclTax($quoteItem->getRowTotalInclTax())
                ->setBaseRegularRowTotalInclTax($quoteItem->getBaseRowTotalInclTax())
                ->setRegularTaxAmount($quoteItem->getTaxAmount())
                ->setBaseRegularTaxAmount($quoteItem->getBaseTaxAmount())
            ;
        }
    }
}
