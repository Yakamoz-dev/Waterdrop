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

use Aheadworks\Sarp2\Model\ResourceModel\Plan as PlanResource;
use Magento\Framework\App\ResourceConnection;
use Magento\TestFramework\Helper\Bootstrap;

$objectManager = Bootstrap::getObjectManager();

/** @var ResourceConnection $resource */
$resource = $objectManager->get(ResourceConnection::class);
$connection = $resource->getConnection();
/** @var PlanResource $resourceModel */
$resourceModel = $objectManager->create(PlanResource::class);
$entityTable = $resourceModel->getTable('aw_sarp2_plan_definition');

$entitiesData = include __DIR__ . '/plan_definitions_data.php';

foreach ($entitiesData as $data) {
    $queryData = $connection->quote($data);
    $connection->query(
        "INSERT INTO {$entityTable} (`definition_id`, `billing_period`, `billing_frequency`,"
        . " `total_billing_cycles`, `start_date_type`, `start_date_day_of_month`, `is_initial_fee_enabled`,"
        . " `is_trial_period_enabled`, `trial_total_billing_cycles`)"
        . " VALUES ({$queryData});"
    );
}
