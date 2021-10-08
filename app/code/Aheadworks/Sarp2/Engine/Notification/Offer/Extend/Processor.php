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
namespace Aheadworks\Sarp2\Engine\Notification\Offer\Extend;

use Aheadworks\Sarp2\Api\Data\ProfileInterface;
use Aheadworks\Sarp2\Engine\Notification\NotificationList;
use Aheadworks\Sarp2\Engine\Notification\Persistence;
use Aheadworks\Sarp2\Engine\Profile\Action\Type\Extend\ValidatorWrapper;
use Exception;
use Magento\Framework\Exception\CouldNotDeleteException;

/**
 * Class Processor
 *
 * @package Aheadworks\Sarp2\Engine\Notification\Offer\Extend
 */
class Processor
{
    /**
     * @var Finder
     */
    private $profileFinder;

    /**
     * @var ValidatorWrapper
     */
    private $extendActionValidator;

    /**
     * @var Scheduler
     */
    private $scheduler;

    /**
     * @var NotificationList
     */
    private $notificationList;

    /**
     * @var Persistence
     */
    private $persistence;

    /**
     * @param Finder $profileFinder
     * @param ValidatorWrapper $extendActionValidator
     * @param Scheduler $scheduler
     * @param NotificationList $notificationList
     * @param Persistence $persistence
     */
    public function __construct(
        Finder $profileFinder,
        ValidatorWrapper $extendActionValidator,
        Scheduler $scheduler,
        NotificationList $notificationList,
        Persistence $persistence
    ) {
        $this->profileFinder = $profileFinder;
        $this->extendActionValidator = $extendActionValidator;
        $this->scheduler = $scheduler;
        $this->notificationList = $notificationList;
        $this->persistence = $persistence;
    }

    /**
     * Process profiles
     */
    public function processProfileWithOfferForToday()
    {
        $profiles = $this->profileFinder->getReadyForSendOfferForToday();

        foreach ($profiles as $profile) {
            if ($this->extendActionValidator->isValid($profile)) {
                try {
                    $this->scheduler->scheduleIfToday($profile);
                } catch (Exception $exception) {
                }
            }
        }
    }

    /**
     * Reschedule offer notification for profile
     *
     * @param ProfileInterface $profile
     * @throws CouldNotDeleteException
     */
    public function rescheduleNotification($profile)
    {
        $this->persistence->massDelete(
            $this->notificationList->getReadyForSendNotificationsForProfile($profile->getProfileId())
        );

        if ($this->extendActionValidator->isValid($profile)) {
            try {
                $this->scheduler->scheduleIfEarlierToday($profile);
            } catch (Exception $exception) {
            }
        }
    }
}
