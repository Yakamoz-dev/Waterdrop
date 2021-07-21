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

require 'plan_definitions.php';

use Aheadworks\Sarp2\Model\ResourceModel\Plan as PlanResource;
use Magento\Framework\App\ResourceConnection;
use Magento\TestFramework\Helper\Bootstrap;

$objectManager = Bootstrap::getObjectManager();

/** @var ResourceConnection $resource */
$resource = $objectManager->get(ResourceConnection::class);
$connection = $resource->getConnection();
/** @var PlanResource $resourceModel */
$resourceModel = $objectManager->create(PlanResource::class);
$entityTable = $resourceModel->getTable('aw_sarp2_plan');

$entitiesData = include __DIR__ . '/plans_data.php';

foreach ($entitiesData as $data) {
    $queryData = $connection->quote($data);
    $connection->query(
        "INSERT INTO {$entityTable} (`plan_id`, `definition_id`, `status`, `name`,"
        . " `regular_price_pattern_percent`, `trial_price_pattern_percent`, `price_rounding`)"
        . " VALUES ({$queryData});"
    );
}
