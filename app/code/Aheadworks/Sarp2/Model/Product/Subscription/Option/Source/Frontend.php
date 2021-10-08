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
namespace Aheadworks\Sarp2\Model\Product\Subscription\Option\Source;

use Aheadworks\Sarp2\Model\Plan\Checker as PlanChecker;
use Aheadworks\Sarp2\Model\Product\Checker\IsSubscription;
use Aheadworks\Sarp2\Model\Product\Subscription\Option\Finder as SubscriptionOptionFinder;

/**
 * Class Frontend
 * @package Aheadworks\Sarp2\Model\Product\Subscription\Option\Source
 */
class Frontend
{
    /**
     * @var IsSubscription
     */
    private $isSubscriptionChecker;

    /**
     * @var SubscriptionOptionFinder
     */
    private $subscriptionOptionFinder;

    /**
     * @var PlanChecker
     */
    private $planChecker;

    /**
     * @param IsSubscription $isSubscriptionChecker
     * @param SubscriptionOptionFinder $subscriptionOptionFinder
     * @param PlanChecker $planChecker
     */
    public function __construct(
        IsSubscription $isSubscriptionChecker,
        SubscriptionOptionFinder $subscriptionOptionFinder,
        PlanChecker $planChecker
    ) {
        $this->isSubscriptionChecker = $isSubscriptionChecker;
        $this->subscriptionOptionFinder = $subscriptionOptionFinder;
        $this->planChecker = $planChecker;
    }

    /**
     * Get frontend options
     *
     * @param int $productId
     * @return array
     */
    public function getOptionArray($productId)
    {
        $optionArray = [];
        if (!$this->isSubscriptionChecker->checkById($productId, true)) {
            $optionArray[0] = __('One-off purchase (No subscription)');
        }

        $options = $this->subscriptionOptionFinder->getSortedOptions($productId);
        foreach ($options as $option) {
            if ($this->planChecker->isEnabled($option->getPlanId())) {
                $optionArray[$option->getOptionId()] = $option->getFrontendTitle();
            }
        }

        return $optionArray;
    }

    /**
     * Get frontend options for plan selection
     *
     * @param int $productId
     * @return array
     */
    public function getPlanOptionArray($productId)
    {
        $optionArray = [];
        $options = $this->subscriptionOptionFinder->getSortedOptions($productId);
        foreach ($options as $option) {
            if ($this->planChecker->isEnabled($option->getPlanId())) {
                $optionArray[$option->getPlanId()] = $option->getFrontendTitle();
            }
        }

        return $optionArray;
    }
}
