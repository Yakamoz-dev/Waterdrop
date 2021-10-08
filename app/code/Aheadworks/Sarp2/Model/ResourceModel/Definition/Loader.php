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
namespace Aheadworks\Sarp2\Model\ResourceModel\Definition;

use Aheadworks\Sarp2\Api\Data\PlanDefinitionInterface;
use Aheadworks\Sarp2\Api\Data\PlanDefinitionInterfaceFactory;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\App\ResourceConnection;

/**
 * Class Loader
 *
 * @package Aheadworks\Sarp2\Model\ResourceModel\Definition
 */
class Loader
{
    /**
     * @var ResourceConnection
     */
    private $resourceConnection;

    /**
     * @var PlanDefinitionInterfaceFactory
     */
    private $planDefinitionFactory;

    /**
     * @var DataObjectHelper
     */
    private $dataObjectHelper;

    /**
     * @param ResourceConnection $resource
     * @param PlanDefinitionInterfaceFactory $planDefinitionFactory
     * @param DataObjectHelper $dataObjectHelper
     */
    public function __construct(
        ResourceConnection $resource,
        PlanDefinitionInterfaceFactory $planDefinitionFactory,
        DataObjectHelper $dataObjectHelper
    ) {
        $this->resourceConnection = $resource;
        $this->planDefinitionFactory = $planDefinitionFactory;
        $this->dataObjectHelper = $dataObjectHelper;
    }

    /**
     * Load plan/profile definition
     *
     * @param int $definitionId
     * @param bool $isPlan
     * @return PlanDefinitionInterface
     */
    public function loadDefinition($definitionId, $isPlan = true)
    {
        $connection = $this->resourceConnection->getConnection();
        $table = $isPlan
            ? $this->resourceConnection->getTableName('aw_sarp2_plan_definition')
            : $this->resourceConnection->getTableName('aw_sarp2_profile_definition');
        $select = $connection->select()
            ->from($table)
            ->where('definition_id = ?', $definitionId);
        $data = $connection->fetchRow($select);

        /** @var PlanDefinitionInterface $definition */
        $definition = $this->planDefinitionFactory->create();
        if ($data) {
            $this->dataObjectHelper->populateWithArray($definition, $data, PlanDefinitionInterface::class);
        }
        return $definition;
    }
}
