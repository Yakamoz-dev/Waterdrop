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
namespace Aheadworks\Sarp2\Model\Quote\Substitute\Quote\Address\Item\Checker;

use Aheadworks\Sarp2\Model\Quote\Item\Checker\IsSubscription\CheckerInterface;
use Aheadworks\Sarp2\Model\Quote\Substitute\Quote\Address\Item
    as SubstituteQuoteAddressItem;

/**
 * Class IsSubscription
 *
 * @package Aheadworks\Sarp2\Model\Quote\Substitute\Quote\Address\Item\Checker
 */
class IsSubscription implements CheckerInterface
{
    /**
     * @inheritDoc
     * @param SubstituteQuoteAddressItem $item
     */
    public function check($item)
    {
        //TODO: update to use ['info_buyRequest']['aw_sarp2_subscription_type'] value if necessary
        return true;
    }
}
