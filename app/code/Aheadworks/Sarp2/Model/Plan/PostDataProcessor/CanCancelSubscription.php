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
namespace Aheadworks\Sarp2\Model\Plan\PostDataProcessor;

use Aheadworks\Sarp2\Api\Data\PlanDefinitionInterface as Definition;

/**
 * Class CanCancelSubscription
 *
 * @package Aheadworks\Sarp2\Model\Plan\PostDataProcessor
 */
class CanCancelSubscription implements ProcessorInterface
{
    /**
     * @inheritdoc
     */
    public function prepareEntityData($data)
    {
        if (isset($data['use_default'][Definition::IS_ALLOW_SUBSCRIPTION_CANCELLATION])
            && (bool)(int)$data['use_default'][Definition::IS_ALLOW_SUBSCRIPTION_CANCELLATION]
        ) {
            $data['definition'][Definition::IS_ALLOW_SUBSCRIPTION_CANCELLATION] = null;
        }

        return $data;
    }
}
