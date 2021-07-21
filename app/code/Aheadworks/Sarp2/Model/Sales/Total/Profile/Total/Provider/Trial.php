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
namespace Aheadworks\Sarp2\Model\Sales\Total\Profile\Total\Provider;

use Aheadworks\Sarp2\Api\Data\ProfileInterface;

/**
 * Class Trial
 * @package Aheadworks\Sarp2\Model\Sales\Total\Profile\Total\Provider
 */
class Trial extends AbstractProvider
{
    /**
     * {@inheritdoc}
     */
    public function getUnitPrice($item, $useBaseCurrency)
    {
        return $useBaseCurrency
            ? $item->getBaseTrialPrice()
            : $item->getTrialPrice();
    }

    /**
     * {@inheritdoc}
     */
    public function getUnitPriceInclTax($item, $useBaseCurrency)
    {
        return $useBaseCurrency
            ? $item->getBaseTrialPriceInclTax()
            : $item->getTrialPriceInclTax();
    }

    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function getShippingAmount($address, $useBaseCurrency)
    {
        $result = 0.0;
        if ($this->hasData('profile')) {
            /** @var ProfileInterface $profile */
            $profile = $this->getData('profile');
            $result = $useBaseCurrency
                ? $profile->getBaseTrialShippingAmount()
                : $profile->getTrialShippingAmount();
        }
        return $result;
    }

    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function getAddressSubtotal($address, $useBaseCurrency)
    {
        $result = 0.0;
        if ($this->hasData('profile')) {
            /** @var ProfileInterface $profile */
            $profile = $this->getData('profile');
            $result = $useBaseCurrency
                ? $profile->getBaseTrialSubtotal()
                : $profile->getTrialSubtotal();
        }
        return $result;
    }

    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function getAddressSubtotalInclTax($address, $useBaseCurrency)
    {
        $result = 0.0;
        if ($this->hasData('profile')) {
            /** @var ProfileInterface $profile */
            $profile = $this->getData('profile');
            $result = $useBaseCurrency
                ? $profile->getBaseTrialSubtotalInclTax()
                : $profile->getTrialSubtotalInclTax();
        }
        return $result;
    }
}
