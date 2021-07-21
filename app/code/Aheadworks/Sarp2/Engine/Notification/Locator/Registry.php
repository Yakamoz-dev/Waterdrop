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
namespace Aheadworks\Sarp2\Engine\Notification\Locator;

use Aheadworks\Sarp2\Engine\NotificationInterface;

/**
 * Class Registry
 * @package Aheadworks\Sarp2\Engine\Notification\Locator
 */
class Registry
{
    /**
     * @var NotificationInterface|null
     */
    private $notification = null;

    /**
     * Set notification instance to registry
     *
     * @param NotificationInterface $notification
     * @return void
     */
    public function register($notification)
    {
        $this->notification = $notification;
    }

    /**
     * Get registered notification instance
     *
     * @return NotificationInterface
     */
    public function get()
    {
        return $this->notification;
    }

    /**
     * Unset registered notification instance
     */
    public function unRegister()
    {
        $this->notification = null;
    }
}
