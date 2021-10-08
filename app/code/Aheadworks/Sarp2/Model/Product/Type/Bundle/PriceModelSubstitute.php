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
namespace Aheadworks\Sarp2\Model\Product\Type\Bundle;

use Magento\Bundle\Model\Product\Price as BundlePrice;

/**
 * Class PriceModelSubstitute
 */
class PriceModelSubstitute extends BundlePrice
{
    const DO_NOT_USE_ADVANCED_PRICES_FOR_BUNDLE = 'do_not_use_advanced_prices';

    /**
     * @inheritDoc
     */
    protected function _applyTierPrice($product, $qty, $finalPrice)
    {
        return $finalPrice;
    }

    /**
     * @inheritDoc
     */
    protected function _applySpecialPrice($product, $finalPrice)
    {
        return $finalPrice;
    }
}
