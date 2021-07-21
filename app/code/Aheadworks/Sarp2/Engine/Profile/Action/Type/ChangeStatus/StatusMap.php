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
namespace Aheadworks\Sarp2\Engine\Profile\Action\Type\ChangeStatus;

use Aheadworks\Sarp2\Model\Profile\Source\Status;

/**
 * Class StatusMap
 * @package Aheadworks\Sarp2\Engine\Profile\Action\Type\ChangeStatus
 */
class StatusMap
{
    /**
     * @var array
     */
    private $map = [
        Status::ACTIVE => [Status::SUSPENDED, Status::CANCELLED],
        Status::SUSPENDED => [Status::ACTIVE, Status::CANCELLED, Status::PENDING],
        Status::PENDING => [Status::ACTIVE, Status::CANCELLED, Status::SUSPENDED],
        Status::EXPIRED => [],
        Status::CANCELLED => [Status::ACTIVE]
    ];

    /**
     * @param array $map
     */
    public function __construct(array $map = [])
    {
        $this->map = array_merge($this->map, $map);
    }

    /**
     * Get all profile statuses
     *
     * @return array
     */
    public function getAllStatuses()
    {
        return array_keys($this->map);
    }

    /**
     * Get allowed profile statuses
     *
     * @param string $status
     * @return array
     */
    public function getAllowedStatuses($status)
    {
        return isset($this->map[$status])
            ? $this->map[$status]
            : [];
    }
}
