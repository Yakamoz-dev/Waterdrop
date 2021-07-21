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
namespace Aheadworks\Sarp2\Model\Sales\Total\Quote\Total\Provider;

use Aheadworks\Sarp2\Model\Sales\Total\ProviderInterface;

/**
 * Class Regular
 * @package Aheadworks\Sarp2\Model\Sales\Total\Quote\Total\Provider
 */
class Regular implements ProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function getUnitPrice($item, $useBaseCurrency)
    {
        return $useBaseCurrency
            ? $item->getBaseAwSarpRegularPrice()
            : $item->getAwSarpRegularPrice();
    }

    /**
     * {@inheritdoc}
     */
    public function getUnitPriceInclTax($item, $useBaseCurrency)
    {
        return $useBaseCurrency
            ? $item->getBaseAwSarpRegularPriceInclTax()
            : $item->getAwSarpRegularPriceInclTax();
    }

    /**
     * {@inheritdoc}
     */
    public function getShippingAmount($address, $useBaseCurrency)
    {
        return $useBaseCurrency
            ? $address->getBaseAwSarpRegularShippingAmount()
            : $address->getAwSarpRegularShippingAmount();
    }

    /**
     * {@inheritdoc}
     */
    public function getAddressSubtotal($address, $useBaseCurrency)
    {
        return $useBaseCurrency
            ? $address->getBaseAwSarpRegularSubtotal()
            : $address->getAwSarpRegularSubtotal();
    }

    /**
     * {@inheritdoc}
     */
    public function getAddressSubtotalInclTax($address, $useBaseCurrency)
    {
        return $useBaseCurrency
            ? $address->getBaseAwSarpRegularSubtotalInclTax()
            : $address->getAwSarpRegularSubtotalInclTax();
    }

    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function getDiscountAmount($item, $useBaseCurrency)
    {
        return 0;
    }
}
