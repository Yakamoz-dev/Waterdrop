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

use Aheadworks\Sarp2\Api\Data\PlanInterface;

/**
 * Class BillingCycles
 *
 * @package Aheadworks\Sarp2\Model\Plan\DataProvider\Processor
 */
class BillingCycles implements ProcessorInterface
{
    /**
     * @inheritDoc
     */
    public function process($data)
    {
        if (!isset($data[PlanInterface::PLAN_ID])) {
            return $data;
        }

        if ($data['definition']['trial_total_billing_cycles'] == 0) {
            $data['definition']['trial_total_billing_cycles'] = null;
        }

        return $data;
    }
}
