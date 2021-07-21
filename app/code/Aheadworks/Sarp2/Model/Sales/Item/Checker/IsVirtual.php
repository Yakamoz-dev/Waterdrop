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
namespace Aheadworks\Sarp2\Model\Sales\Item\Checker;

use Magento\Quote\Model\Quote\Item as QuoteItem;
use Magento\Sales\Model\Order\Item as OrderItem;

/**
 * Class IsVirtual
 * @package Aheadworks\Sarp2\Model\Sales\Item\Checker
 */
class IsVirtual
{
    /**
     * Check if items presents a virtual quote/order
     *
     * @param QuoteItem[]|OrderItem[] $items
     * @return bool
     */
    public function check($items)
    {
        foreach ($items as $item) {
            if (!$item->getIsVirtual()) {
                return false;
            }
        }
        return true;
    }
}
