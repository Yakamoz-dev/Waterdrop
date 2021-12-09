<?php
/**
 * Magedelight
 * Copyright (C) 2019 Magedelight <info@magedelight.com>
 *
 * @category  Magedelight
 * @package   Magedelight_SMSProfile
 * @copyright Copyright (c) 2019 Mage Delight (http://www.magedelight.com/)
 * @license   http://opensource.org/licenses/gpl-3.0.html GNU General Public License,version 3 (GPL-3.0)
 * @author    Magedelight <info@magedelight.com>
 */

namespace Magedelight\Bundlediscount\Api;

use Magento\Framework\Exception\NoSuchEntityException;

/**
 * @api
 */
interface BundlePromoAddInterface
{
    /**
     * Get bundle details by cart id
     *
     * @param string $cartId The cart ID.
     * @return mixed
     * @throws \Magento\Framework\Exception\NoSuchEntityException The specified cart does not exist.
     */
    public function getBundleByCart($cartId);

    /**
     * Add bundle product to the cart.
     *
     * @param int $bundleId
     * @param int $storeId
     * @param int $customerId
     * @return string
     * @throws NoSuchEntityException
     */
    public function AddBundlePromoToCart($bundleId, $storeId,$customerId);
}