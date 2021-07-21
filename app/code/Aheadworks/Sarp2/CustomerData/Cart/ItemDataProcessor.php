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
namespace Aheadworks\Sarp2\CustomerData\Cart;

use Aheadworks\Sarp2\Model\Quote\Item\Checker\IsSubscription;
use Magento\Quote\Model\Quote\Item;

/**
 * Class ItemDataProcessor
 * @package Aheadworks\Sarp2\CustomerData\Cart
 */
class ItemDataProcessor
{
    /**
     * @var IsSubscription
     */
    private $isSubscriptionChecker;

    /**
     * @param IsSubscription $isSubscriptionChecker
     */
    public function __construct(IsSubscription $isSubscriptionChecker)
    {
        $this->isSubscriptionChecker = $isSubscriptionChecker;
    }

    /**
     * Process cart item data
     *
     * @param Item $item
     * @param array $data
     * @return array
     */
    public function process(Item $item, array $data)
    {
        $isSubscription = $this->isSubscriptionChecker->check($item);
        $data['aw_sarp_is_subscription'] = $isSubscription;
        if ($isSubscription) {
            $optionId = $item->getOptionByCode('aw_sarp2_subscription_type');
            $parentOptionId = $item->getOptionByCode('aw_sarp2_parent_subscription_type');
            if ($parentOptionId) {
                $data['aw_sarp_subscription_type'] = $parentOptionId->getValue();
            } elseif ($optionId) {
                $data['aw_sarp_subscription_type'] = $optionId->getValue();
            }
        }
        return $data;
    }
}
