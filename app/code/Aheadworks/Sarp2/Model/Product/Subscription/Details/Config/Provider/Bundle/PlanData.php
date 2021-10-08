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
namespace Aheadworks\Sarp2\Model\Product\Subscription\Details\Config\Provider\Bundle;

use Aheadworks\Sarp2\Api\Data\ProfileInterface;
use Aheadworks\Sarp2\Api\Data\ProfileItemInterface;
use Aheadworks\Sarp2\Api\PlanRepositoryInterface;
use Aheadworks\Sarp2\Api\SubscriptionOptionRepositoryInterface;
use Aheadworks\Sarp2\Model\Product\Subscription\Details\Config\Provider\ConfigInterface;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Framework\Exception\LocalizedException;

class PlanData implements ConfigInterface
{
    /**
     * @var SubscriptionOptionRepositoryInterface
     */
    private $optionsRepository;

    /**
     * @var PlanRepositoryInterface
     */
    private $planRepository;

    /**
     * @param SubscriptionOptionRepositoryInterface $optionRepository
     * @param PlanRepositoryInterface $planRepository
     */
    public function __construct(
        SubscriptionOptionRepositoryInterface $optionRepository,
        PlanRepositoryInterface $planRepository
    ) {
        $this->optionsRepository = $optionRepository;
        $this->planRepository = $planRepository;
    }

    /**
     * Get option plan data
     *
     * @param ProductInterface $product
     * @param ProfileItemInterface|null $item
     * @param ProfileInterface|null $profile
     * @return array|null
     * @throws LocalizedException
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function getConfig($product, $item = null, $profile = null)
    {
        $config = [];
        $subscriptionOptions = $this->optionsRepository->getList($product->getId());

        foreach ($subscriptionOptions as $subscriptionOption) {
            $plan = $this->planRepository->get($subscriptionOption->getPlanId());

            $config[$subscriptionOption->getOptionId()] = [
                'trialPercent' => (float)$plan->getTrialPricePatternPercent(),
                'regularPercent' => (float)$plan->getRegularPricePatternPercent()
            ];
        }

        return $config;
    }
}
