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
namespace Aheadworks\Sarp2\Model\Sales\Total\Profile\Collector\Shipping\Adapter;

use Aheadworks\Sarp2\Api\Data\ProfileItemInterface;
use Aheadworks\Sarp2\Model\Quote\Substitute\Quote\Address as AddressSubstitute;
use Magento\Quote\Model\Quote\Address\FreeShippingInterface;

/**
 * Class FreeShipping
 * @package Aheadworks\Sarp2\Model\Sales\Total\Profile\Collector\Shipping\Adapter
 */
class FreeShipping
{
    /**
     * @var FreeShippingInterface
     */
    private $freeShipping;

    /**
     * @param FreeShippingInterface $freeShipping
     */
    public function __construct(FreeShippingInterface $freeShipping)
    {
        $this->freeShipping = $freeShipping;
    }

    /**
     * Check if free shipping available
     *
     * @param AddressSubstitute $addressSubstitute
     * @return bool
     */
    public function isFreeShipping($addressSubstitute)
    {
        $quoteSubstitute = $addressSubstitute->getQuote();
        $quoteSubstitute->setShippingAddress($addressSubstitute);

        $items = $addressSubstitute->getAllItems();
        $result = $this->freeShipping->isFreeShipping($addressSubstitute->getQuote(), $items);

        foreach ($items as $item) {
            /** @var ProfileItemInterface $profileItem */
            $profileItem = $item->getLinkedProfileItem();
            if ($profileItem) {
                $profileItem->setIsFreeShipping((bool)$item->getFreeShipping());
            }
        }

        return $result;
    }
}
