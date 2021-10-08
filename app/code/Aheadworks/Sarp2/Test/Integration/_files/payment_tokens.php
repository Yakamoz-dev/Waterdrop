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

use Aheadworks\Sarp2\Model\ResourceModel\Payment\Token as TokenResource;
use Magento\Framework\App\ResourceConnection;
use Magento\TestFramework\Helper\Bootstrap;

$objectManager = Bootstrap::getObjectManager();

/** @var ResourceConnection $resource */
$resource = $objectManager->get(ResourceConnection::class);
$connection = $resource->getConnection();
/** @var TokenResource $resourceModel */
$resourceModel = $objectManager->create(TokenResource::class);
$entityTable = $resourceModel->getTable('aw_sarp2_payment_token');

$entitiesData = include __DIR__ . '/payment_tokens_data.php';

foreach ($entitiesData as $data) {
    $queryData = $connection->quote($data);
    $connection->query(
        "INSERT INTO {$entityTable} (`token_id`, `payment_method`, `type`, `token_value`, `created_at`,"
        . " `expires_at`, `is_active`)"
        . " VALUES ({$queryData});"
    );
}
