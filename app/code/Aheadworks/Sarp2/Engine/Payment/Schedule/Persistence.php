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
namespace Aheadworks\Sarp2\Engine\Payment\Schedule;

use Aheadworks\Sarp2\Engine\Payment\Schedule;
use Aheadworks\Sarp2\Engine\Payment\ScheduleFactory;
use Aheadworks\Sarp2\Engine\Payment\ScheduleInterface;
use Aheadworks\Sarp2\Model\ResourceModel\Engine\Schedule as ScheduleResource;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Class Persistence
 * @package Aheadworks\Sarp2\Engine\Payment\Schedule
 */
class Persistence
{
    /**
     * @var ScheduleResource
     */
    private $resource;

    /**
     * @var ScheduleFactory
     */
    private $scheduleFactory;

    /**
     * @var array
     */
    private $instancesById = [];

    /**
     * @var array
     */
    private $instancesByProfileId = [];

    /**
     * @param ScheduleResource $resource
     * @param ScheduleFactory $scheduleFactory
     */
    public function __construct(
        ScheduleResource $resource,
        ScheduleFactory $scheduleFactory
    ) {
        $this->resource = $resource;
        $this->scheduleFactory = $scheduleFactory;
    }

    /**
     * Retrieve schedule instance
     *
     * @param int $scheduleId
     * @return Schedule
     * @throws NoSuchEntityException
     */
    public function get($scheduleId)
    {
        if (!isset($this->instancesById[$scheduleId])) {
            /** @var Schedule $schedule */
            $schedule = $this->scheduleFactory->create();
            $this->resource->load($schedule, $scheduleId);
            if (!$schedule->getScheduleId()) {
                throw NoSuchEntityException::singleField('scheduleId', $scheduleId);
            }
            $this->instancesById[$scheduleId] = $schedule;
            $this->instancesByProfileId[$schedule->getProfileId()] = $schedule;
        }
        return $this->instancesById[$scheduleId];
    }

    /**
     * Retrieve schedule instance by profile id
     *
     * @param int $profileId
     * @return Schedule
     * @throws NoSuchEntityException
     */
    public function getByProfile($profileId)
    {
        if (!isset($this->instancesByProfileId[$profileId])) {
            /** @var Schedule $schedule */
            $schedule = $this->scheduleFactory->create();
            $this->resource->load($schedule, $profileId, ScheduleInterface::PROFILE_ID);
            if (!$schedule->getScheduleId()) {
                throw NoSuchEntityException::singleField('profileId', $profileId);
            }
            $this->instancesByProfileId[$profileId] = $schedule;
            $this->instancesById[$schedule->getScheduleId()] = $schedule;
        }
        return $this->instancesByProfileId[$profileId];
    }

    /**
     * Save schedule
     *
     * @param ScheduleInterface $schedule
     * @return Schedule
     * @throws CouldNotSaveException
     * @throws NoSuchEntityException
     */
    public function save($schedule)
    {
        try {
            $this->resource->save($schedule);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__($exception->getMessage()));
        }
        $scheduleId = $schedule->getScheduleId();
        $profileId = $schedule->getProfileId();
        unset($this->instancesById[$scheduleId]);
        unset($this->instancesByProfileId[$profileId]);

        return $this->get($scheduleId);
    }
}
