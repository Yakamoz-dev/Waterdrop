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
namespace Aheadworks\Sarp2\Model\Sales\Total\Quote\Subtotal;

use Aheadworks\Sarp2\Model\Quote\Item\Checker\IsSubscription;
use Aheadworks\Sarp2\Model\Sales\Total\GroupInterface;
use Aheadworks\Sarp2\Model\Sales\Total\Quote\Grand\Summator;
use Magento\Framework\DataObject\Factory;
use Magento\Quote\Api\Data\AddressInterface;
use Magento\Quote\Api\Data\CartInterface;
use Magento\Quote\Api\Data\CartItemInterface;
use Magento\Quote\Api\Data\ShippingAssignmentInterface;
use Magento\Quote\Model\Quote;
use Magento\Quote\Model\QuoteValidator;
use Magento\Quote\Model\Quote\Address;
use Magento\Quote\Model\Quote\Address\Item as AddressItem;
use Magento\Quote\Model\Quote\Address\Total;
use Magento\Quote\Model\Quote\Address\Total\AbstractTotal;

/**
 * Class Collector
 * @package Aheadworks\Sarp2\Model\Sales\Total\Quote\Subtotal
 */
class Collector extends AbstractTotal
{
    /**
     * @var IsSubscription
     */
    private $isSubscriptionChecker;

    /**
     * @var GroupInterface
     */
    private $totalsGroup;

    /**
     * @var QuoteValidator
     */
    private $quoteValidator;

    /**
     * @var Factory
     */
    private $dataObjectFactory;

    /**
     * @var Summator
     */
    private $grandSummator;

    /**
     * @param IsSubscription $isSubscriptionChecker
     * @param GroupInterface $totalsGroup
     * @param QuoteValidator $quoteValidator
     * @param Factory $dataObjectFactory
     * @param Summator $grandSummator
     */
    public function __construct(
        IsSubscription $isSubscriptionChecker,
        GroupInterface $totalsGroup,
        QuoteValidator $quoteValidator,
        Factory $dataObjectFactory,
        Summator $grandSummator
    ) {
        $this->isSubscriptionChecker = $isSubscriptionChecker;
        $this->totalsGroup = $totalsGroup;
        $this->quoteValidator = $quoteValidator;
        $this->dataObjectFactory = $dataObjectFactory;
        $this->grandSummator = $grandSummator;
    }

    /**
     * {@inheritdoc}
     */
    public function collect(
        Quote $quote,
        ShippingAssignmentInterface $shippingAssignment,
        Total $total
    ) {
        parent::collect($quote, $shippingAssignment, $total);

        /** @var Address $address */
        $address = $shippingAssignment->getShipping()->getAddress();
        $items = $shippingAssignment->getItems();
        if ($items) {
            $baseSubtotal = 0;
            $subtotal = 0;

            foreach ($items as $item) {
                $quoteItem = $item instanceof AddressItem
                    ? $item->getAddress()->getQuote()->getItemById($item->getQuoteItemId())
                    : $item;

                $isSubscription = $this->isSubscriptionChecker->check($quoteItem);
                $basePrice = $isSubscription
                    ? $this->totalsGroup->getItemPrice($quoteItem, true)
                    : 0;
                $price = $isSubscription
                    ? $this->totalsGroup->getItemPrice($quoteItem, false)
                    : 0;

                $qty = $item->getQty();
                $baseRowTotal = $basePrice * $qty;
                $rowTotal = $price * $qty;

                $this->totalsGroup->getPopulator(CartItemInterface::class)
                    ->populate(
                        $quoteItem,
                        $this->dataObjectFactory->create(
                            [
                                'price' => $basePrice,
                                'row_total' => $baseRowTotal
                            ]
                        )
                    );

                $baseSubtotal += $baseRowTotal;
                $subtotal += $rowTotal;
            }

            $this->quoteValidator->validateQuoteAmount($quote, $baseSubtotal);
            $this->quoteValidator->validateQuoteAmount($quote, $subtotal);

            $this->totalsGroup->getPopulator(CartInterface::class)
                ->populate(
                    $quote,
                    $this->dataObjectFactory->create(['subtotal' => $baseSubtotal])
                );
            $this->totalsGroup->getPopulator(AddressInterface::class)
                ->populate(
                    $address,
                    $this->dataObjectFactory->create(['subtotal' => $baseSubtotal])
                );
            $this->grandSummator->setAmount(
                $this->totalsGroup->getCode() . '_subtotal',
                $baseSubtotal
            );
        }
    }

    /**
     * {@inheritdoc}
     */
    public function fetch(Quote $quote, Total $total)
    {
        return null;
    }
}
