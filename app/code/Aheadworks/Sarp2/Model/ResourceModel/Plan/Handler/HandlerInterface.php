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
namespace Aheadworks\Sarp2\Model\ResourceModel\Plan\Handler;

use Aheadworks\Sarp2\Api\Data\PlanInterface;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

/**
 * Interface HandlerInterface
 *
 * @package Aheadworks\Sarp2\Model\ResourceModel\Plan
 */
interface HandlerInterface
{
    /**
     * Process plan saving/deletion
     *
     * @param AbstractDb $resourceModel
     * @param PlanInterface $plan
     * @return void
     */
    public function process(AbstractDb $resourceModel, PlanInterface $plan);
}
