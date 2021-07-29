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
namespace Aheadworks\Sarp2\Model\Sales\Total\Profile\Collector;

use Aheadworks\Sarp2\Api\Data\ProfileInterface;
use Aheadworks\Sarp2\Api\Data\ProfileItemInterface;
use Aheadworks\Sarp2\Model\Sales\Total\GroupInterface;
use Aheadworks\Sarp2\Model\Sales\Total\PopulatorInterface;
use Aheadworks\Sarp2\Model\Sales\Total\Profile\CollectorInterface;
use Aheadworks\Sarp2\Model\Sales\Total\Profile\Collector\Grand\Summator;
use Magento\Bundle\Model\Product\Type as BundleType;
use Magento\Framework\DataObject\Factory;

/**
 * Class Subtotal
 * @package Aheadworks\Sarp2\Model\Sales\Total\Profile\Collector
 */
class Subtotal implements CollectorInterface
{
    /**
     * @var GroupInterface
     */
    private $totalsGroup;

    /**
     * @var Factory
     */
    private $dataObjectFactory;

    /**
     * @var Summator
     */
    private $grandSummator;

    /**
     * @param GroupInterface $totalsGroup
     * @param Factory $dataObjectFactory
     * @param Summator $grandSummator
     */
    public function __construct(
        GroupInterface $totalsGroup,
        Factory $dataObjectFactory,
        Summator $grandSummator
    ) {
        $this->totalsGroup = $totalsGroup;
        $this->dataObjectFactory = $dataObjectFactory;
        $this->grandSummator = $grandSummator;
    }

    /**
     * {@inheritdoc}
     */
    public function collect(ProfileInterface $profile)
    {
        $baseSubtotal = 0;
        $itemQty = 0;
        $currencyOptionConvert = PopulatorInterface::CURRENCY_OPTION_CONVERT;

        foreach ($profile->getItems() as $item) {
            $itemBasePrice = $this->totalsGroup->getItemPrice($item, true);
            $baseRowTotal = $itemBasePrice * $item->getQty();
            $totalsDetails = $this->dataObjectFactory->create(
                [
                    'row_total' => $baseRowTotal,
                    'price' => $itemBasePrice
                ]
            );
            $this->totalsGroup->getPopulator(ProfileItemInterface::class)
                ->populate($item, $totalsDetails, $currencyOptionConvert);

            $baseSubtotal += $baseRowTotal;
            $itemQty += $item->getQty();

            // only for configurable
            if ($item->hasChildItems() && $item->getProductType() != BundleType::TYPE_CODE) {
                foreach ($item->getChildItems() as &$child) {
                    $this->totalsGroup->getPopulator(ProfileItemInterface::class)
                        ->populate($child, $totalsDetails, $currencyOptionConvert);
                }
            }
        }

        $profile->setItemsQty($itemQty);
        $this->totalsGroup->getPopulator(ProfileInterface::class)
            ->populate(
                $profile,
                $this->dataObjectFactory->create(['subtotal' => $baseSubtotal]),
                $currencyOptionConvert
            );
        $this->grandSummator->setAmount(
            $this->totalsGroup->getCode() . '_subtotal',
            $baseSubtotal
        );
    }
}