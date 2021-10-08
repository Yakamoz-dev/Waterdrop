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
namespace Aheadworks\Sarp2\Model\Config;

use Aheadworks\Sarp2\Api\Data\PlanDefinitionInterface;
use Aheadworks\Sarp2\Model\Config;

/**
 * Class CanCancelSubscriptionValueResolver
 *
 * @package Aheadworks\Sarp2\Model\Config
 */
class CanCancelSubscriptionValueResolver
{
    /**
     * @var Config
     */
    private $config;

    /**
     * @param Config $config
     */
    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    /**
     * Retrieve boolean flag that determines availability of cancel subscription
     *
     * @param PlanDefinitionInterface $planDefinition
     * @return bool
     */
    public function canCancelSubscription($planDefinition)
    {
        $isAllowCancelSubscription = $planDefinition->getIsAllowSubscriptionCancellation();
        if (null === $isAllowCancelSubscription) {
            $isAllowCancelSubscription = $this->config->canCancelSubscription();
        }

        return (bool)$isAllowCancelSubscription;
    }
}
