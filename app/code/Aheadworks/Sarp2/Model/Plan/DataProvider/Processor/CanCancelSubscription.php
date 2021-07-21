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
namespace Aheadworks\Sarp2\Model\Plan\DataProvider\Processor;

use Aheadworks\Sarp2\Api\Data\PlanDefinitionInterface as Definition;
use Aheadworks\Sarp2\Model\Config;

/**
 * Class CanCancelSubscription
 *
 * @package Aheadworks\Sarp2\Model\Plan\DataProvider\Processor
 */
class CanCancelSubscription implements ProcessorInterface
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
     * @inheritDoc
     */
    public function process($data)
    {
        $isAllowSubscriptionCancellation = $data['definition'][Definition::IS_ALLOW_SUBSCRIPTION_CANCELLATION] ?? null;
        if (null === $isAllowSubscriptionCancellation) {
            $data['definition'][Definition::IS_ALLOW_SUBSCRIPTION_CANCELLATION]
                = (string)(int)$this->config->canCancelSubscription();
            $data['definition']['use_default_' . Definition::IS_ALLOW_SUBSCRIPTION_CANCELLATION] = '1';
        }

        return $data;
    }
}
