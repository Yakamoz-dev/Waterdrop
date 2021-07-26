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
namespace Aheadworks\Sarp2\Model\Sales\Total\Quote;

use Aheadworks\Sarp2\Model\Quote\Checker\HasSubscriptions;
use Magento\Framework\Pricing\PriceCurrencyInterface;
use Magento\Quote\Api\Data\ShippingAssignmentInterface;
use Magento\Quote\Model\Quote;
use Magento\Quote\Model\Quote\Address\FreeShippingInterface;
use Magento\Quote\Model\Quote\Address\Total;
use Magento\Quote\Model\Quote\Address\Total\Shipping as Collector;

/**
 * Class Shipping
 * @package Aheadworks\Sarp2\Model\Sales\Total\Quote
 */
class Shipping extends Collector
{
    /**
     * @var HasSubscriptions
     */
    private $quoteChecker;

    /**
     * @param PriceCurrencyInterface $priceCurrency
     * @param FreeShippingInterface $freeShipping
     * @param HasSubscriptions $quoteChecker
     */
    public function __construct(
        PriceCurrencyInterface $priceCurrency,
        FreeShippingInterface $freeShipping,
        HasSubscriptions $quoteChecker
    ) {
        parent::__construct($priceCurrency, $freeShipping);
        $this->quoteChecker = $quoteChecker;
    }

    /**
     * {@inheritdoc}
     */
    public function collect(
        Quote $quote,
        ShippingAssignmentInterface $shippingAssignment,
        Total $total
    ) {
        if ($this->quoteChecker->checkHasSubscriptionsOnly($quote)) {
            $address = $shippingAssignment->getShipping()->getAddress();
            $address->setWeight(0);
            $address->setFreeMethodWeight(0);

            $total->setTotalAmount($this->getCode(), 0);
            $total->setBaseTotalAmount($this->getCode(), 0);

            $total->setBaseShippingAmount(0);
            $total->setShippingAmount(0);
        } elseif ($this->quoteChecker->checkHasBoth($quote)) {
            // todo: M2SARP-345
        } else {
            parent::collect($quote, $shippingAssignment, $total);
        }

        return $this;
    }
}
