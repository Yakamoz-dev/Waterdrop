<?php
namespace Ecopure\Tongtool\Setup;

class InstallSchema implements \Magento\Framework\Setup\InstallSchemaInterface
{

    public function install(\Magento\Framework\Setup\SchemaSetupInterface $setup, \Magento\Framework\Setup\ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();
        /* tongtool_order table */
        if (!$installer->tableExists('tongtool_order')) {
            $table = $installer->getConnection()->newTable(
                $installer->getTable('tongtool_order')
            )
                ->addColumn(
                    'id',
                    \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                    null,
                    [
                        'identity' => true,
                        'nullable' => false,
                        'primary'  => true,
                        'unsigned' => true,
                    ],
                    'ID'
                )
                ->addColumn(
                    'order_id',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    255,
                    [],
                    'Order ID'
                )
                ->addColumn(
                    'magento_id',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    255,
                    [],
                    'Magento ID'
                )
                ->addColumn(
                    'order_items',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    255,
                    [],
                    'Order Items'
                )
                ->addColumn(
                    'customer_name',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    255,
                    [],
                    'Customer Name'
                )
                ->addColumn(
                    'email',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    160,
                    [],
                    'Email'
                )
                ->addColumn(
                    'address1',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    160,
                    [],
                    'Address1'
                )
                ->addColumn(
                    'address2',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    160,
                    [],
                    'Address2'
                )
                ->addColumn(
                    'address3',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    160,
                    [],
                    'Address3'
                )
                ->addColumn(
                    'state',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    60,
                    [],
                    'State'
                )
                ->addColumn(
                    'zip',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    60,
                    [],
                    'Zip'
                )
                ->addColumn(
                    'country',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    60,
                    [],
                    'Country'
                )
                ->addColumn(
                    'phone',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    60,
                    [],
                    'Phone'
                )
                ->addColumn(
                    'shipping_method',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    255,
                    [],
                    'Shipping Method'
                )
                ->addColumn(
                    'tracking_no',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    255,
                    [],
                    'Tracking No'
                )
                ->addColumn(
                    'is_cancelled',
                    \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                    1,
                    [],
                    'Is Cancelled'
                )
                ->addColumn(
                    'is_completed',
                    \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                    1,
                    [],
                    'Is Completed'
                )
                ->setComment('Tongtool Order');
            $installer->getConnection()->createTable($table);
        }
        $installer->endSetup();
    }
}
