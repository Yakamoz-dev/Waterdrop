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
namespace Aheadworks\Sarp2\Model\Profile\View\Adminhtml\OrderInfo;

use Aheadworks\Sarp2\Engine\Payment\Schedule\Checker;
use Aheadworks\Sarp2\Engine\Payment\Schedule\Persistence;

/**
 * Class Counter
 *
 * @package Aheadworks\Sarp2\Model\Profile\View\Adminhtml\OrderInfo
 */
class Counter
{
    /**
     * @var Persistence
     */
    private $schedulePersistence;

    /**
     * @var Checker
     */
    private $scheduleChecker;

    /**
     * @param Persistence $schedulePersistence
     * @param Checker $scheduleChecker
     */
    public function __construct(
        Persistence $schedulePersistence,
        Checker $scheduleChecker
    ) {
        $this->schedulePersistence = $schedulePersistence;
        $this->scheduleChecker = $scheduleChecker;
    }

    /**
     * Count left orders
     *
     * @param int $profileId
     * @return bool|int
     */
    public function countLeftOrders($profileId)
    {
        $ordersLeft = 0;
        try {
            $schedule = $this->schedulePersistence->getByProfile($profileId);

            if ($this->scheduleChecker->isFiniteSubscription($schedule)) {
                $ordersLeft = $schedule->getRegularTotalCount()
                    + $schedule->getTrialTotalCount()
                    - $schedule->getTrialCount()
                    - $schedule->getRegularCount();
            }
        } catch (\Exception $e) {
            return false;
        }

        return $ordersLeft;
    }
}
