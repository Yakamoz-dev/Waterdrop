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
namespace Aheadworks\Sarp2\Engine\Profile\Action\Type\ChangeStatus\Status;

use Aheadworks\Sarp2\Engine\Profile\Action\Type\ChangeStatus\Status\Type\Active;
use Aheadworks\Sarp2\Engine\Profile\Action\Type\ChangeStatus\Status\Type\Cancelled;
use Aheadworks\Sarp2\Engine\Profile\Action\Type\ChangeStatus\Status\Type\DefaultStatus;
use Aheadworks\Sarp2\Model\Profile\Source\Status;

/**
 * Class StatusApplierPool
 *
 * @package Aheadworks\Sarp2\Engine\Profile\Action\Type\ChangeStatus\Applier
 */
class StatusApplierPool
{
    /**
     * @var StatusApplierInterface[]
     */
    private $applierInstances = [];

    /**
     * @var array
     */
    private $appliers = [
        Status::ACTIVE => Active::class,
        Status::CANCELLED => Cancelled::class,
    ];

    /**
     * @var StatusApplierFactory
     */
    private $applierFactory;

    /**
     * @param StatusApplierFactory $applierFactory
     * @param array $appliers
     */
    public function __construct(
        StatusApplierFactory $applierFactory,
        array $appliers = []
    ) {
        $this->applierFactory = $applierFactory;
        $this->appliers = array_merge($this->appliers, $appliers);
    }

    /**
     * Get action applier instance
     *
     * @param string $status
     * @return StatusApplierInterface
     */
    public function getApplier($status)
    {
        if (!isset($this->applierInstances[$status])) {
            $this->applierInstances[$status] = $this->applierFactory->create(
                $this->appliers[$status] ?? DefaultStatus::class
            );
        }
        return $this->applierInstances[$status];
    }
}
