<?php
namespace Ecopure\Gift\Setup;

class InstallSchema implements \Magento\Framework\Setup\InstallSchemaInterface
{

    public function install(\Magento\Framework\Setup\SchemaSetupInterface $setup, \Magento\Framework\Setup\ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();
        /* ro */
        if (!$installer->tableExists('ro')) {
            $table = $installer->getConnection()->newTable(
                $installer->getTable('ro')
            )
                ->addColumn(
                    'ro_id',
                    \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                    null,
                    [
                        'identity' => true,
                        'nullable' => false,
                        'primary'  => true,
                        'unsigned' => true,
                    ],
                    'Ro ID'
                )
                ->addColumn(
                    'ro_productid',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    255,
                    [],
                    'Product ID'
                )
                ->addColumn(
                    'ro_model_no',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    255,
                    [],
                    'Model No'
                )
                ->addColumn(
                    'ro_asin',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    255,
                    [],
                    'Asin'
                )
                ->addColumn(
                    'ro_order_no',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    255,
                    [],
                    'Order Number'
                )
                ->addColumn(
                    'ro_used',
                    \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                    1,
                    [],
                    'Used'
                )
                ->addColumn(
                    'ro_regd',
                    \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                    1,
                    [],
                    'Regd'
                )
                ->addColumn(
                    'use_order',
                    \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                    1,
                    [],
                    'Use Order'
                )
                ->setComment('Ro Table');
            $installer->getConnection()->createTable($table);
        }
        /* ro address */
        if (!$installer->tableExists('ro_address')) {
            $table = $installer->getConnection()->newTable(
                $installer->getTable('ro_address')
            )
                ->addColumn(
                    'address_id',
                    \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                    null,
                    [
                        'identity' => true,
                        'nullable' => false,
                        'primary'  => true,
                        'unsigned' => true,
                    ],
                    'Ro ID'
                )
                ->addColumn(
                    'customer_id',
                    \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                    10,
                    [],
                    'Customer ID'
                )
                ->addColumn(
                    'first_name',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    60,
                    [],
                    'First Name'
                )
                ->addColumn(
                    'last_name',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    60,
                    [],
                    'Last Name'
                )
                ->addColumn(
                    'city',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    60,
                    [],
                    'City'
                )
                ->addColumn(
                    'country',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    60,
                    [],
                    'Country'
                )
                ->addColumn(
                    'email',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    60,
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
                    'state',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    60,
                    [],
                    'State'
                )
                ->addColumn(
                    'telephone',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    60,
                    [],
                    'Telephone'
                )
                ->addColumn(
                    'zip',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    60,
                    [],
                    'Zip'
                )
                ->addColumn(
                    'remark',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    60,
                    [],
                    'Remark'
                )
                ->setComment('Ro Address Table');
            $installer->getConnection()->createTable($table);
        }
        /* ro customer */
        if (!$installer->tableExists('ro_customer')) {
            $table = $installer->getConnection()->newTable(
                $installer->getTable('ro_customer')
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
                    'Ro Customer ID'
                )
                ->addColumn(
                    'ro_id',
                    \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                    11,
                    [],
                    'Customer ID'
                )
                ->addColumn(
                    'first_name',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    255,
                    [],
                    'First Name'
                )
                ->addColumn(
                    'last_name',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    255,
                    [],
                    'Last Name'
                )
                ->addColumn(
                    'email',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    255,
                    [],
                    'Email'
                )
                ->addColumn(
                    'installation_data',
                    \Magento\Framework\DB\Ddl\Table::TYPE_DATETIME,
                    null,
                    [],
                    'Installation Data'
                )
                ->addColumn(
                    'purchase_date',
                    \Magento\Framework\DB\Ddl\Table::TYPE_DATETIME,
                    null,
                    [],
                    'Purchase Date'
                )
                ->addColumn(
                    'telephone',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    20,
                    [],
                    'Telephone'
                )
                ->addColumn(
                    'order_number',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    255,
                    [],
                    'Order Number'
                )
                ->addColumn(
                    'product_id',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    255,
                    [],
                    'Product ID'
                )
                ->addColumn(
                    'model_no',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    255,
                    [],
                    'Model No.'
                )
                ->addColumn(
                    'rating_star',
                    \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                    11,
                    [],
                    'Rating Star'
                )
                ->addColumn(
                    'rating_comment',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    '64k',
                    [],
                    'Rating Comment.'
                )
                ->addColumn(
                    'device',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    255,
                    [],
                    'Device'
                )
                ->addColumn(
                    'channel',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    255,
                    [],
                    'Channel'
                )
                ->addColumn(
                    'comment_time',
                    \Magento\Framework\DB\Ddl\Table::TYPE_DATETIME,
                    null,
                    [],
                    'Comment Time'
                )
                ->setComment('Ro Customer Table');
            $installer->getConnection()->createTable($table);
        }
        /* ro order */
        if (!$installer->tableExists('ro_order')) {
            $table = $installer->getConnection()->newTable(
                $installer->getTable('ro_order')
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
                    'Ro Order ID'
                )
                ->addColumn(
                    'product_id',
                    \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                    11,
                    [],
                    'Product ID'
                )
                ->addColumn(
                    'options',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    255,
                    [],
                    'Options'
                )
                ->addColumn(
                    'shipped',
                    \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                    1,
                    [],
                    'Shipped'
                )
                ->addColumn(
                    'customer_id',
                    \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                    11,
                    [],
                    'Customer ID'
                )
                ->setComment('Ro Order Table');
            $installer->getConnection()->createTable($table);
        }
        /* ro product */
        if (!$installer->tableExists('ro_product')) {
            $table = $installer->getConnection()->newTable(
                $installer->getTable('ro_product')
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
                    'Ro Order ID'
                )
                ->addColumn(
                    'enable',
                    \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                    1,
                    [],
                    'Enable'
                )
                ->addColumn(
                    'ro_model_no',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    255,
                    [],
                    'Model No.'
                )
                ->addColumn(
                    'name',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    255,
                    [],
                    'Name'
                )
                ->addColumn(
                    'image',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    255,
                    [],
                    'Image'
                )
                ->addColumn(
                    'description',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    '64k',
                    [],
                    'Description'
                )
                ->addColumn(
                    'price',
                    \Magento\Framework\DB\Ddl\Table::TYPE_FLOAT,
                    '10,2',
                    ['default' => 0],
                    'Price'
                )
                ->addColumn(
                    'options',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    '64k',
                    [],
                    'Options'
                )
                ->setComment('Ro Product Table');
            $installer->getConnection()->createTable($table);
        }
        $installer->endSetup();
    }
}
