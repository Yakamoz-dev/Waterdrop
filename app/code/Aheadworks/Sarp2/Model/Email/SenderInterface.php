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
namespace Aheadworks\Sarp2\Model\Email;

use Aheadworks\Sarp2\Engine\NotificationInterface;
use Aheadworks\Sarp2\Model\Email\Send\ResultInterface;

/**
 * Class Sender
 * @package Aheadworks\Sarp2\Model\Email
 */
interface SenderInterface
{
    /**
     * Send email notification if enabled
     *
     * @param NotificationInterface $notification
     * @return ResultInterface
     */
    public function sendIfEnabled(NotificationInterface $notification);
}
