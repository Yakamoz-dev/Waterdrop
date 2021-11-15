<?php

/**
 * Magedelight
 * Copyright (C) 2016 Magedelight <info@magedelight.com>.
 *
 * NOTICE OF LICENSE
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see http://opensource.org/licenses/gpl-3.0.html.
 *
 * @category Magedelight
 *
 * @copyright Copyright (c) 2016 Mage Delight (http://www.magedelight.com/)
 * @license http://opensource.org/licenses/gpl-3.0.html GNU General Public License,version 3 (GPL-3.0)
 * @author Magedelight <info@magedelight.com>
 */

namespace Magedelight\Bundlediscount\Setup;

use Magento\Framework\Setup\UpgradeSchemaInterface;

use Magento\Framework\Setup\ModuleContextInterface;

use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Adapter\AdapterInterface;

class UpgradeSchema implements UpgradeSchemaInterface
{

    public function upgrade(
        SchemaSetupInterface $setup,
        ModuleContextInterface $context
    ) {

        $setup->startSetup();

        if (version_compare($context->getVersion(), '1.3.5') < 0) {
        // Get module table
            $tableName = $setup->getTable('md_bundlediscount_bundles');
        // Check if the table already exists
            if ($setup->getConnection()->isTableExists($tableName) == true) {
            // Declare data
                $columns = [
                'bundle_option' => [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                'nullable' => true,
                'comment' => 'Bundle Option',
                ],
                ];
                $connection = $setup->getConnection();
                foreach ($columns as $name => $definition) {
                    $connection->addColumn($tableName, $name, $definition);
                }
            }

        // Get module table
            $tableName = $setup->getTable('md_bundlediscount_bundles');
        // Check if the table already exists
            if ($setup->getConnection()->isTableExists($tableName) == true) {
            // Declare data
                $columns = [
                'bundle_keywords' => [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                'nullable' => true,
                'comment' => 'Bundle Keywords',
                ],
                ];
                $connection = $setup->getConnection();
                foreach ($columns as $name => $definition) {
                    $connection->addColumn($tableName, $name, $definition);
                }
            }

        // Get module table
            $tableName = $setup->getTable('md_bundlediscount_bundles');
        // Check if the table already exists
            if ($setup->getConnection()->isTableExists($tableName) == true) {
            // Declare data
                $columns = [
                'bundle_tags' => [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                'nullable' => true,
                'comment' => 'Bundle Tags',
                ],
                ];
                $connection = $setup->getConnection();
                foreach ($columns as $name => $definition) {
                    $connection->addColumn($tableName, $name, $definition);
                }
            }

            $installer = $setup;
            $installer->startSetup();

            $table = $installer->getConnection()->newTable(
                $installer->getTable('md_bundle_tag_category')
            )->addColumn(
                'entity_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'nullable' => false, 'primary' => true],
                'Id'
            )->addColumn(
                'name',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable' => false],
                'Title'
            )->addColumn(
                'is_active',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                [],
                'Active Status'
            )->addColumn(
                'created_at',
                \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                null,
                ['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT],
                'Creation Time'
            )->addColumn(
                'update_time',
                \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                null,
                ['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT_UPDATE],
                'Modification Time'
            )->setComment(
                'Row Data Table'
            );
 
                $installer->getConnection()->createTable($table);
                $installer->endSetup();

                $table = $installer->getConnection()->newTable(
                    $installer->getTable('md_bundle_tag_item')
                )->addColumn(
                    'id',
                    \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                    null,
                    ['identity' => true, 'nullable' => false, 'primary' => true],
                    'Id'
                )->addColumn(
                    'category',
                    \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                    null,
                    ['nullable' => false],
                    'Category Record Id'
                )->addColumn(
                    'name',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    255,
                    ['nullable' => false],
                    'Title'
                )->addColumn(
                    'is_active',
                    \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                    null,
                    [],
                    'Active Status'
                )->addColumn(
                    'created_at',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                    null,
                    ['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT],
                    'Creation Time'
                )->addColumn(
                    'update_time',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                    null,
                    ['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT_UPDATE],
                    'Modification Time'
                )->addIndex(
                    $setup->getIdxName(
                        $installer->getTable('md_bundle_tag_item'),
                        ['name'],
                        AdapterInterface::INDEX_TYPE_FULLTEXT
                    ),
                    ['name'],
                    ['type' => AdapterInterface::INDEX_TYPE_FULLTEXT]
                )->setComment(
                    'Row Data Table'
                );
 
                $installer->getConnection()->createTable($table);
                $installer->endSetup();
        }
        $setup->endSetup();
    }
}
