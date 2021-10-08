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
namespace Aheadworks\Sarp2\Model\Product\Checker\IsSubscription\Type;

use Magento\Catalog\Api\Data\ProductInterface;

/**
 * Interface HandlerInterface
 * @package Aheadworks\Sarp2\Model\Product\Checker\IsSubscription\Type
 */
interface HandlerInterface
{
    /**
     * Check if subscribe action available for product
     *
     * @param ProductInterface $product
     * @param bool $subscriptionOnly
     * @return bool
     */
    public function check($product, $subscriptionOnly = false);
}
