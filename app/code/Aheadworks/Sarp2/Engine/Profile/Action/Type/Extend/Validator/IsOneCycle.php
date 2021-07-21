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
namespace Aheadworks\Sarp2\Engine\Profile\Action\Type\Extend\Validator;

use Aheadworks\Sarp2\Api\Data\ProfileInterface;
use Aheadworks\Sarp2\Engine\Payment\Schedule\Persistence;
use Aheadworks\Sarp2\Engine\Payment\ScheduleInterface;
use Aheadworks\Sarp2\Engine\Profile\Action\Validation\AbstractValidator;

/**
 * Class IsOneCycle
 *
 * @package Aheadworks\Sarp2\Engine\Profile\Action\Type\Extend\Validator
 */
class IsOneCycle extends AbstractValidator
{
    /**
     * @var Persistence
     */
    private $schedulePersistence;

    /**
     * @param Persistence $schedulePersistence
     */
    public function __construct(Persistence $schedulePersistence)
    {
        $this->schedulePersistence = $schedulePersistence;
    }

    /**
     * @inheritDoc
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    protected function performValidation($profile, $action)
    {
        $profileDefinition = $profile->getProfileDefinition();
        if ($profileDefinition->getTotalBillingCycles() == 1
            && !$this->issetSchedule($profile)
        ) {
            $this->addMessages(['This is subscription with one billing cycle.']);
        }
    }

    /**
     * Check if isset schedule for profile
     *
     * @param ProfileInterface $profile
     * @return bool
     */
    private function issetSchedule($profile)
    {
        try {
            $schedule = $this->schedulePersistence->getByProfile($profile->getProfileId());
            $result = $schedule instanceof ScheduleInterface;
        } catch (\Exception $exception) {
            $result = false;
        }

        return $result;
    }
}
