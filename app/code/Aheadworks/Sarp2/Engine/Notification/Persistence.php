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
use Aheadworks\Sarp2\Engine\NotificationFactory;
use Aheadworks\Sarp2\Model\ResourceModel\Engine\Notification as NotificationResource;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;

/**
 * Class Persistence
 * @package Aheadworks\Sarp2\Engine\Notification
 */
class Persistence
{
    /**
     * @var NotificationResource
     */
    private $resource;

    /**
     * @var NotificationFactory
     */
    private $notificationFactory;

    /**
     * @param NotificationResource $resource
     * @param NotificationFactory $notificationFactory
     */
    public function __construct(
        NotificationResource $resource,
        NotificationFactory $notificationFactory
    ) {
        $this->resource = $resource;
        $this->notificationFactory = $notificationFactory;
    }

    /**
     * Save notification
     *
     * @param Notification $notification
     * @return Notification
     * @throws CouldNotSaveException
     */
    public function save(Notification $notification)
    {
        try {
            $this->resource->save($notification);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__($exception->getMessage()));
        }
        return $notification;
    }

    /**
     * Delete notification
     *
     * @param Notification $notification
     * @return bool
     * @throws CouldNotDeleteException
     */
    public function delete(Notification $notification)
    {
        try {
            $this->resource->delete($notification);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__($exception->getMessage()));
        }
        return true;
    }

    /**
     * Perform mass payments delete
     *
     * @param Notification[] $notifications
     * @throws CouldNotDeleteException
     */
    public function massDelete($notifications)
    {
        try {
            $this->resource->massDelete($notifications);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__($exception->getMessage()));
        }
    }
}
