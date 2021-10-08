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

use Aheadworks\Sarp2\Model\ResourceModel\Engine\Notification as NotificationResource;
use Magento\Framework\Stdlib\DateTime;

/**
 * Class Cleaner
 *
 * @package Aheadworks\Sarp2\Engine\Notification
 */
class Cleaner
{
    /**
     * @var DateTime
     */
    private $dateTime;

    /**
     * @var NotificationResource
     */
    private $resource;

    /**
     * @var int
     */
    private $storageTimeInMonth;

    /**
     * @param DateTime $dateTime
     * @param NotificationResource $resource
     * @param int $storageTimeInMonth
     */
    public function __construct(
        DateTime $dateTime,
        NotificationResource $resource,
        int $storageTimeInMonth = 3
    ) {
        $this->dateTime = $dateTime;
        $this->resource = $resource;
        $this->storageTimeInMonth = $storageTimeInMonth;
    }

    /**
     * CleanUp older messages
     *
     * @return int
     */
    public function cleanUp()
    {
        try {
            $date = new \DateTime('now');
            $date->modify('- ' . $this->storageTimeInMonth . ' months');

            return $this->resource->cleanUpObsoleteMessages($this->dateTime->formatDate($date));
        } catch (\Exception $exception) {
            return 0;
        }
    }
}
