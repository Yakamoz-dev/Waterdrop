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
namespace Aheadworks\Sarp2\Model\Profile\Item\Checker;

use Aheadworks\Sarp2\Api\Data\ProfileItemInterface;
use Aheadworks\Sarp2\Model\Profile\Item;

/**
 * Class IsOneOffItem
 * @package Aheadworks\Sarp2\Model\Profile\Item\Checker
 */
class IsOneOffItem
{
    /**
     * Check is one-off profile item
     *
     * @param ProfileItemInterface $profileItem
     * @return bool
     */
    public function check(ProfileItemInterface $profileItem): bool
    {
        $productOptions = $profileItem->getProductOptions();

        return isset($productOptions['info_buyRequest'][Item::ONE_OFF_ITEM_OPTION])
            || (!isset($productOptions['info_buyRequest']['aw_sarp2_subscription_option'])
                && !isset($productOptions['aw_sarp2_subscription_option']['option_id'])
                && !isset($productOptions['info_buyRequest']['aw_sarp2_subscription_type']));
    }
}