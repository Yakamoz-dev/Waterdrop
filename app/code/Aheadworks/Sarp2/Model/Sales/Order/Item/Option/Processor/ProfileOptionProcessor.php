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
namespace Aheadworks\Sarp2\Model\Sales\Order\Item\Option\Processor;

use Aheadworks\Sarp2\Api\Data\PlanInterface;
use Aheadworks\Sarp2\Api\Data\PlanInterfaceFactory;
use Aheadworks\Sarp2\Api\Data\ProfileItemInterface as ProfileItem;
use Aheadworks\Sarp2\Model\Plan\Resolver\TitleResolver as PlanTitleResolver;
use Aheadworks\Sarp2\Model\Profile\Finder as ProfileFinder;
use Aheadworks\Sarp2\Model\Profile\Item\Finder as ProfileItemsFinder;
use Aheadworks\Sarp2\ViewModel\Subscription\Details\ForProfile as ProfileDetailsViewModel;
use Aheadworks\Sarp2\ViewModel\Subscription\Details\ForProfileItem as ProfileItemDetailsViewModel;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\App\State as AppState;
use Magento\Sales\Model\Order\Item as OrderItem;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class ProfileOptionProcessor
 *
 * @package Aheadworks\Sarp2\Model\Sales\Order\Item\Option
 */
class ProfileOptionProcessor implements ProcessorInterface
{
    /**
     * @var PlanInterfaceFactory
     */
    private $planFactory;

    /**
     * @var PlanTitleResolver
     */
    private $planTitleResolver;

    /**
     * @var DataObjectHelper
     */
    private $dataObjectHelper;

    /**
     * @var ProfileItemDetailsViewModel
     */
    private $itemDetailsViewModel;

    /**
     * @var ProfileDetailsViewModel
     */
    private $profileDetailsViewModel;

    /**
     * @var ProfileFinder
     */
    private $profileFinder;

    /**
     * @var ProfileItemsFinder
     */
    private $profileItemsFinder;

    /**
     * @var AppState
     */
    private $appState;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @param PlanInterfaceFactory $planFactory
     * @param PlanTitleResolver $planTitleResolver
     * @param DataObjectHelper $dataObjectHelper
     * @param ProfileFinder $profileFinder
     * @param ProfileItemsFinder $profileItemsFinder
     * @param ProfileItemDetailsViewModel $itemDetailsViewModel
     * @param ProfileDetailsViewModel $profileDetailsViewModel
     * @param AppState $appState
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        PlanInterfaceFactory $planFactory,
        PlanTitleResolver $planTitleResolver,
        DataObjectHelper $dataObjectHelper,
        ProfileFinder $profileFinder,
        ProfileItemsFinder $profileItemsFinder,
        ProfileItemDetailsViewModel $itemDetailsViewModel,
        ProfileDetailsViewModel $profileDetailsViewModel,
        AppState $appState,
        StoreManagerInterface $storeManager
    ) {
        $this->planFactory = $planFactory;
        $this->planTitleResolver = $planTitleResolver;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->profileFinder = $profileFinder;
        $this->profileItemsFinder = $profileItemsFinder;
        $this->itemDetailsViewModel = $itemDetailsViewModel;
        $this->profileDetailsViewModel = $profileDetailsViewModel;
        $this->appState = $appState;
        $this->storeManager = $storeManager;
    }

    /**
     * Check if an order item is a subscription
     *
     * @param array $options
     * @return bool
     */
    public function isSubscription($options)
    {
        if (isset($options['aw_sarp2_subscription_plan'])
            && is_array($options['aw_sarp2_subscription_plan'])
        ) {
            return true;
        }

        return false;
    }

    /**
     * @inheritDoc
     */
    public function process(OrderItem $orderItem, array $options)
    {
        $subscriptionOptions = [];
        if ($this->isSubscription($options)) {
            $plan = $this->getPlan($options);
            if ($profile = $this->profileFinder->getByOrderAndPlan($orderItem->getOrderId(), $plan->getPlanId())) {
                $profileItems = $this->profileItemsFinder->getItemsWithHiddenReplaced($profile);
                $planTitle = $this->getPlanTitle($plan);
                $profileItem = $this->getProfileItemByOrderItem($profileItems, $orderItem);

                $addOption = function ($label, $value) use (&$subscriptionOptions, $plan) {
                    $subscriptionOptions[] = [
                        'label' => $label,
                        'value' => $value,
                        'aw_sarp2_subscription_plan' => $plan->getPlanId(),
                        // require for reorder from customer account
                        'option_id' => null,
                        'option_value' => null
                    ];
                };

                $addOption(
                    __('Subscription Plan'),
                    $planTitle
                );
                $addOption(
                    __('Subscription Created On'),
                    $this->profileDetailsViewModel->getCreatedDate($profile)
                );

                if ($profileItem) {
                    if ($this->itemDetailsViewModel->isShowInitialDetails($profileItem)) {
                        $addOption(
                            $this->itemDetailsViewModel->getInitialLabel(),
                            $this->itemDetailsViewModel->getInitialPaymentPrice($profileItem)
                        );
                    }
                    if ($this->itemDetailsViewModel->isShowTrialDetails($profileItem)) {
                        $addOption(
                            $this->itemDetailsViewModel->getTrialLabel($profileItem),
                            __('%1 starting %2', [
                                $this->itemDetailsViewModel->getTrialPriceAndCycles($profileItem),
                                $this->itemDetailsViewModel->getTrialStartDate($profileItem)
                            ])
                        );
                    }
                    if ($this->itemDetailsViewModel->isShowRegularDetails($profileItem)) {
                        $addOption(
                            $this->itemDetailsViewModel->getRegularLabel($profileItem),
                            __('%1 starting %2', [
                                $this->itemDetailsViewModel->getRegularPriceAndCycles($profileItem),
                                $this->itemDetailsViewModel->getRegularStartDate($profileItem)
                            ])
                        );
                    }
                }

                $addOption(
                    $this->profileDetailsViewModel->getSubscriptionEndLabel(),
                    $this->profileDetailsViewModel->getRegularStopDate($profile)
                );
            }
        }

        if (isset($options['options'])) {
            $this->removeSubscriptionOptions($options['options']);
            $options['options'] = array_merge($options['options'], $subscriptionOptions);
        } else {
            $options['options'] = $subscriptionOptions;
        }

        return $options;
    }

    /**
     * Create plan object by plan data array
     *
     * @param array $options
     * @return PlanInterface
     */
    private function getPlan($options)
    {
        $planData = $options['aw_sarp2_subscription_plan'];
        /** @var PlanInterface $plan */
        $plan = $this->planFactory->create();
        $this->dataObjectHelper->populateWithArray($plan, $planData, PlanInterface::class);

        return $plan;
    }

    /**
     * Retrieve plan title
     *
     * @param PlanInterface $plan
     * @return string
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    private function getPlanTitle($plan)
    {
        $storeId = $this->storeManager->getStore()->getId();

        return $this->isAdmin() ? $plan->getName() : $this->planTitleResolver->getTitle($plan, $storeId);
    }

    /**
     * Check if admin app state
     *
     * @return bool
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    private function isAdmin()
    {
        return $this->appState->getAreaCode() == 'adminhtml';
    }

    /**
     * Retrieve profile item by order item
     *
     * @param ProfileItem $profileItems
     * @param OrderItem $orderItem
     * @return ProfileItem|null
     */
    private function getProfileItemByOrderItem($profileItems, $orderItem)
    {
        foreach ($profileItems as $profileItem) {
            if ($profileItem->getProductId() == $orderItem->getProductId()
                && $profileItem->getSku() == $orderItem->getSku()
            ) {
                return $profileItem;
            }
        }

        return null;
    }

    /**
     * Remove subscription options
     *
     * @param array $options
     * @return array
     */
    public function removeSubscriptionOptions(&$options)
    {
        foreach ($options as $index => $optionData) {
            if (isset($optionData['aw_sarp2_subscription_plan'])) {
                unset($options[$index]);
            }
        }

        return $options;
    }
}
