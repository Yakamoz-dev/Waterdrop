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
namespace Aheadworks\Sarp2\Plugin\Block;

use Magento\Framework\Pricing\SaleableInterface;
use Aheadworks\Sarp2\Model\Product\Attribute\Source\SubscriptionType;
use Magento\Framework\Pricing\Render;

/**
 * Class PriceRenderPlugin
 * @package Aheadworks\Sarp2\Plugin\Block
 */
class PriceRenderPlugin
{
    /**
     * Subscription price type
     */
    const AW_SARP2_SUBSCRIPTION_PRICE_TYPE = 'aw_sarp2_catalog_subscription_price';

    /**
     * Set custom price renderer for only subscription products
     *
     * @param Render $subject
     * @param string $priceCode
     * @param SaleableInterface $saleableItem
     * @param array $arguments
     * @return array
     */
    public function beforeRender($subject, $priceCode, SaleableInterface $saleableItem, array $arguments = [])
    {
        if (isset($arguments['zone'])
            && $arguments['zone'] == Render::ZONE_ITEM_LIST
            && $saleableItem->getData('aw_sarp2_subscription_type') == SubscriptionType::SUBSCRIPTION_ONLY
        ) {
            return [self::AW_SARP2_SUBSCRIPTION_PRICE_TYPE, $saleableItem, $arguments];
        }

        return [$priceCode, $saleableItem, $arguments];
    }
}
