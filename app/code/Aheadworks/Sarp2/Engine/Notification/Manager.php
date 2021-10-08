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
namespace Aheadworks\Sarp2\Engine\Notification;

use Aheadworks\Sarp2\Engine\Notification;
use Aheadworks\Sarp2\Engine\NotificationInterface;
use Aheadworks\Sarp2\Engine\Notification\DataResolver\ResolveSubject;
use Aheadworks\Sarp2\Engine\Notification\DataResolver\ResolveSubjectFactory;
use Aheadworks\Sarp2\Engine\Notification\Scheduler\Pool;
use Aheadworks\Sarp2\Engine\PaymentInterface;
use Magento\Framework\Exception\CouldNotSaveException;

/**
 * Class Manager
 * @package Aheadworks\Sarp2\Engine\Notification
 */
class Manager
{
    /**
     * @var Pool
     */
    private $schedulerPool;

    /**
     * @var Persistence
     */
    private $persistence;

    /**
     * @var DataResolver
     */
    private $dataResolver;

    /**
     * @var ResolveSubjectFactory
     */
    private $resolveSubjectFactory;

    /**
     * @var NotificationList
     */
    private $notificationList;

    /**
     * @param Pool $schedulerPool
     * @param Persistence $persistence
     * @param DataResolver $dataResolver
     * @param ResolveSubjectFactory $resolveSubjectFactory
     * @param NotificationList $notificationList
     */
    public function __construct(
        Pool $schedulerPool,
        Persistence $persistence,
        DataResolver $dataResolver,
        ResolveSubjectFactory $resolveSubjectFactory,
        NotificationList $notificationList
    ) {
        $this->schedulerPool = $schedulerPool;
        $this->persistence = $persistence;
        $this->dataResolver = $dataResolver;
        $this->resolveSubjectFactory = $resolveSubjectFactory;
        $this->notificationList = $notificationList;
    }

    /**
     * Schedule notification of specified type
     *
     * @param string $type
     * @param PaymentInterface $sourcePayment
     * @param array $additionalData
     * @return NotificationInterface|null
     */
    public function schedule($type, $sourcePayment, $additionalData = [])
    {
        return $this->schedulerPool->getScheduler($type)
            ->schedule($sourcePayment, $additionalData);
    }

    /**
     * Schedule notification of specified type
     *
     * @param string $type
     * @param PaymentInterface[] $sourcePayments
     * @param array $additionalData
     * @return Notification[]|null
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     */
    public function reschedule($type, $sourcePayments, $additionalData = [])
    {
        $notifications = [];
        foreach ($sourcePayments as $sourcePayment) {
            $this->persistence->massDelete(
                $this->notificationList->getReadyForSendNotificationsForProfile($sourcePayment->getProfileId())
            );
            $notifications[] = $this->schedule($type, $sourcePayment, $additionalData);
        }
        return $notifications;
    }

    /**
     * Update notification data
     *
     * @param NotificationInterface $notification
     * @param array $subjectData
     * @return void
     */
    public function updateNotificationData($notification, $subjectData)
    {
        /** @var ResolveSubject $resolveSubject */
        $resolveSubject = $this->resolveSubjectFactory->create($subjectData);
        $notification->setNotificationData($this->dataResolver->resolve($resolveSubject));

        try {
            /** @var Notification $notification */
            $this->persistence->save($notification);
        } catch (CouldNotSaveException $exception) {
        }
    }
}
