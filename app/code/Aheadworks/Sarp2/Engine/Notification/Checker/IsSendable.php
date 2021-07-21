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
namespace Aheadworks\Sarp2\Engine\Notification\Checker;

use Aheadworks\Sarp2\Engine\NotificationInterface;
use Aheadworks\Sarp2\Model\Profile\Source\Status;

/**
 * Class IsSendable
 * @package Aheadworks\Sarp2\Engine\Notification\Checker
 */
class IsSendable
{
    /**
     * @var array
     */
    private $typeToProfileStatusesRestrictedMap = [
        NotificationInterface::TYPE_UPCOMING_BILLING => [
            Status::CANCELLED,
            Status::EXPIRED,
            Status::SUSPENDED
        ]
    ];

    /**
     * Check if notification is sendable
     *
     * @param NotificationInterface $notification
     * @return bool
     */
    public function check(NotificationInterface $notification)
    {
        $notificationType = $notification->getType();
        return isset($this->typeToProfileStatusesRestrictedMap[$notificationType])
            ? !in_array(
                $notification->getProfileStatus(),
                $this->typeToProfileStatusesRestrictedMap[$notificationType]
            )
            : true;
    }
}
