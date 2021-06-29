<?php

namespace Ecopure\Gift\Setup;

use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;

class UpgradeSchema implements UpgradeSchemaInterface
{
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();
        if (version_compare($context->getVersion(), '1.0.1', '<')) {
            $tableName = $setup->getTable('ro');
            if ($setup->getConnection()->isTableExists($tableName) == true) {
                $connection = $setup->getConnection();
                $connection->changeColumn(
                    $tableName,
                    'ro_used',
                    'ro_used',
                    ['type' => Table::TYPE_SMALLINT, 'default' => '0', 'comment' => 'Used']
                );
                $connection->changeColumn(
                    $tableName,
                    'ro_regd',
                    'ro_regd',
                    ['type' => Table::TYPE_SMALLINT, 'default' => '0', 'comment' => 'Regd']
                );
                $connection->changeColumn(
                    $tableName,
                    'use_order',
                    'use_order',
                    ['type' => Table::TYPE_SMALLINT, 'default' => '0', 'comment' => 'Use Order']
                );
            }
            $tableName = $setup->getTable('ro_order');
            if ($setup->getConnection()->isTableExists($tableName) == true) {
                $connection = $setup->getConnection();
                $connection->changeColumn(
                    $tableName,
                    'shipped',
                    'shipped',
                    ['type' => Table::TYPE_SMALLINT, 'comment' => 'Shipped']
                );
            }
            $tableName = $setup->getTable('ro_product');
            if ($setup->getConnection()->isTableExists($tableName) == true) {
                $connection = $setup->getConnection();
                $connection->addColumn(
                    $tableName,
                    'out_stock',
                    ['type' => Table::TYPE_SMALLINT, 'afters' => 'enable', 'comment' => 'Out Stcok']
                );
                $connection->changeColumn(
                    $tableName,
                    'enable',
                    'enable',
                    ['type' => Table::TYPE_SMALLINT, 'comment' => 'Enable']
                );
            }
        }

        if (version_compare($context->getVersion(), '1.0.2', '<')) {
            $tableName = $setup->getTable('ro');
            if ($setup->getConnection()->isTableExists($tableName) == true) {
                $connection = $setup->getConnection();
                $connection->addColumn(
                    $tableName,
                    'only_reg',
                    ['type' => Table::TYPE_SMALLINT, 'default' => '0', 'afters' => 'use_order', 'comment' => 'Only Reg']
                );
                $connection->addColumn(
                    $tableName,
                    'created_at',
                    ['type' => Table::TYPE_DATETIME, 'afters' => 'only_reg', 'comment' => 'Created At']
                );
            }
        }

        if (version_compare($context->getVersion(), '1.0.3', '<')) {
            $tableName = $setup->getTable('ro');
            if ($setup->getConnection()->isTableExists($tableName) == true) {
                $connection = $setup->getConnection();
                $connection->addColumn(
                    $tableName,
                    'country',
                    ['type' => Table::TYPE_TEXT, 'length' => 255, 'afters' => 'only_reg', 'comment' => 'Country']
                );
            }
        }

        $setup->endSetup();

    }
}
