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
namespace Aheadworks\Sarp2\Model\Sales\Total\Quote\Grand;

use Aheadworks\Sarp2\Model\Sales\Total\GroupInterface;
use Magento\Framework\DataObject\Factory;
use Magento\Quote\Api\Data\AddressInterface;
use Magento\Quote\Api\Data\CartInterface;
use Magento\Quote\Api\Data\ShippingAssignmentInterface;
use Magento\Quote\Model\Quote;
use Magento\Quote\Model\Quote\Item;
use Magento\Quote\Model\Quote\Address;
use Magento\Quote\Model\Quote\Address\Total;
use Magento\Quote\Model\Quote\Address\Total\AbstractTotal;

/**
 * Class Collector
 * @package Aheadworks\Sarp2\Model\Sales\Total\Quote\Grand
 */
class Collector extends AbstractTotal
{
    /**
     * @var Summator
     */
    private $grandSummator;

    /**
     * @var GroupInterface
     */
    private $totalsGroup;

    /**
     * @var Factory
     */
    private $dataObjectFactory;

    /**
     * @param Summator $grandSummator
     * @param GroupInterface $totalsGroup
     * @param Factory $dataObjectFactory
     */
    public function __construct(
        Summator $grandSummator,
        GroupInterface $totalsGroup,
        Factory $dataObjectFactory
    ) {
        $this->grandSummator = $grandSummator;
        $this->totalsGroup = $totalsGroup;
        $this->dataObjectFactory = $dataObjectFactory;
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
        $baseGrandTotal = $this->grandSummator->getSum($this->totalsGroup->getCode());

        $this->totalsGroup->getPopulator(CartInterface::class)
            ->populate(
                $quote,
                $this->dataObjectFactory->create(['grand_total' => $baseGrandTotal])
            );
        $this->totalsGroup->getPopulator(AddressInterface::class)
            ->populate(
                $address,
                $this->dataObjectFactory->create(['grand_total' => $baseGrandTotal])
            );
    }

    /**
     * {@inheritdoc}
     */
    public function fetch(Quote $quote, Total $total)
    {
        return null;
    }
}
