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
namespace Aheadworks\Sarp2\Model\Plan\PostDataProcessor;

use Aheadworks\Sarp2\Api\Data\PlanDefinitionInterface as Definition;

/**
 * Class Reminder
 *
 * @package Aheadworks\Sarp2\Model\Plan\PostDataProcessor
 */
class Reminder implements ProcessorInterface
{
    /**
     * @inheritdoc
     */
    public function prepareEntityData($data)
    {
        if (isset($data['use_default'][Definition::UPCOMING_BILLING_EMAIL_OFFSET])
            && (bool)$data['use_default'][Definition::UPCOMING_BILLING_EMAIL_OFFSET]
        ) {
            $data['definition'][Definition::UPCOMING_BILLING_EMAIL_OFFSET] = null;
        }

        if (isset($data['use_default'][Definition::UPCOMING_TRIAL_BILLING_EMAIL_OFFSET])
            && (bool)$data['use_default'][Definition::UPCOMING_TRIAL_BILLING_EMAIL_OFFSET]
        ) {
            $data['definition'][Definition::UPCOMING_TRIAL_BILLING_EMAIL_OFFSET] = null;
        }

        return $data;
    }
}
