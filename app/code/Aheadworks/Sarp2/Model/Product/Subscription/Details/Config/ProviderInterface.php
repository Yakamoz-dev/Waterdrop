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
namespace Aheadworks\Sarp2\Model\Product\Subscription\Details\Config;

use Aheadworks\Sarp2\Api\Data\ProfileInterface;
use Aheadworks\Sarp2\Api\Data\ProfileItemInterface;

/**
 * Interface ProviderInterface
 * @package Aheadworks\Sarp2\Model\Product\Subscription\Details\Config
 */
interface ProviderInterface
{
    /**
     * Get subscription details config for product
     *
     * @param int $productId
     * @param string $productTypeId
     * @param ProfileItemInterface|null $item
     * @return array
     */
    public function getConfig($productId, $productTypeId, $item = null);

    /**
     * Get subscription details config
     *
     * @param int $productId
     * @param ProfileItemInterface|null $item
     * @param ProfileInterface $profile
     * @return array
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getSubscriptionDetailsConfig($productId, $item = null, $profile = null);
}
