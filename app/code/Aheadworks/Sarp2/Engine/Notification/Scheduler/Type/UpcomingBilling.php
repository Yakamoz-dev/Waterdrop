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
namespace Aheadworks\Sarp2\Engine\Notification\Scheduler\Type;

use Aheadworks\Sarp2\Engine\Notification;
use Aheadworks\Sarp2\Engine\Notification\DataResolver;
use Aheadworks\Sarp2\Engine\Notification\DataResolver\ResolveSubject;
use Aheadworks\Sarp2\Engine\Notification\DataResolver\ResolveSubjectFactory;
use Aheadworks\Sarp2\Engine\Notification\Persistence;
use Aheadworks\Sarp2\Engine\Notification\SchedulerInterface;
use Aheadworks\Sarp2\Engine\NotificationFactory;
use Aheadworks\Sarp2\Engine\NotificationInterface;
use Aheadworks\Sarp2\Engine\PaymentInterface;
use Aheadworks\Sarp2\Model\Plan\Resolver\Definition\ValueResolver;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Stdlib\DateTime;
use Magento\Framework\Stdlib\DateTime\DateTime as CoreDate;

/**
 * Class UpcomingBilling
 * @package Aheadworks\Sarp2\Engine\Notification\Scheduler\Type
 */
class UpcomingBilling implements SchedulerInterface
{
    /**
     * @var NotificationFactory
     */
    private $notificationFactory;

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
     * @var DateTime
     */
    private $dateTime;

    /**
     * @var CoreDate
     */
    private $coreDate;

    /**
     * @var ValueResolver
     */
    private $definitionValueResolver;

    /**
     * @param NotificationFactory $notificationFactory
     * @param Persistence $persistence
     * @param DataResolver $dataResolver
     * @param ResolveSubjectFactory $resolveSubjectFactory
     * @param DateTime $dateTime
     * @param CoreDate $coreDate
     * @param ValueResolver $definitionValueResolver
     */
    public function __construct(
        NotificationFactory $notificationFactory,
        Persistence $persistence,
        DataResolver $dataResolver,
        ResolveSubjectFactory $resolveSubjectFactory,
        DateTime $dateTime,
        CoreDate $coreDate,
        ValueResolver $definitionValueResolver
    ) {
        $this->notificationFactory = $notificationFactory;
        $this->persistence = $persistence;
        $this->dataResolver = $dataResolver;
        $this->resolveSubjectFactory = $resolveSubjectFactory;
        $this->dateTime = $dateTime;
        $this->coreDate = $coreDate;
        $this->definitionValueResolver = $definitionValueResolver;
    }

    /**
     * {@inheritdoc}
     * @throws \Exception
     */
    public function schedule($sourcePayment, $additionalData)
    {
        $profile = $sourcePayment->getProfile();
        $storeId = $profile->getStoreId();
        $profileDefinition = $profile->getProfileDefinition();

        $offset = $this->definitionValueResolver->getUpcomingEmailOffset($profileDefinition, $storeId);
        if ($offset && $sourcePayment->getType() != PaymentInterface::TYPE_LAST_PERIOD_HOLDER) {
            $estimated = (new \DateTime($sourcePayment->getScheduledAt()))
                ->modify('-' . $offset . ' day');
            $estimatedTm = $this->coreDate->gmtTimestamp($estimated);
            $today = $this->dateTime->formatDate(true);
            $todayTm = $this->coreDate->gmtTimestamp($today);
            if ($estimatedTm >= $todayTm) {
                /** @var Notification $notification */
                $notification = $this->notificationFactory->create();
                $notification->setType(NotificationInterface::TYPE_UPCOMING_BILLING)
                    ->setStatus(NotificationInterface::STATUS_READY)
                    ->setEmail($profile->getCustomerEmail())
                    ->setName($profile->getCustomerFullname())
                    ->setScheduledAt($estimated)
                    ->setStoreId($storeId)
                    ->setProfileId($sourcePayment->getProfileId());

                /** @var ResolveSubject $resolveSubject */
                $resolveSubject = $this->resolveSubjectFactory->create(
                    [
                        'sourcePayment' => $sourcePayment,
                        'nextPayments' => [$sourcePayment]
                    ]
                );
                $notification->setNotificationData($this->dataResolver->resolve($resolveSubject));

                try {
                    $this->persistence->save($notification);
                    return $notification;
                } catch (CouldNotSaveException $exception) {
                }
            }
        }

        return null;
    }
}
