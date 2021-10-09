<?php
/**
 * Mirasvit
 *
 * This source file is subject to the Mirasvit Software License, which is available at https://mirasvit.com/license/.
 * Do not edit or add to this file if you wish to upgrade the to newer versions in the future.
 * If you wish to customize this module for your needs.
 * Please refer to http://www.magentocommerce.com for more information.
 *
 * @category  Mirasvit
 * @package   mirasvit/module-fraud-check
 * @version   1.1.5
 * @copyright Copyright (C) 2021 Mirasvit (https://mirasvit.com/)
 */



namespace Mirasvit\FraudCheck\Setup\UpgradeSchema;

use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\UpgradeSchemaInterface;


class UpgradeSchema102 implements UpgradeSchemaInterface
{

    /**
     * {@inheritdoc}
     */
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->getConnection()->modifyColumn(
            $installer->getTable('sales_order'),
            'fraud_score',
            [
                'type'     => Table::TYPE_INTEGER,
                'nullable' => true,
                'comment'  => 'Fraud Check Score Calculation',
            ]
        );
        $installer->getConnection()->addIndex(
            $installer->getTable('sales_order'),
            $installer->getIdxName(
                $installer->getTable('sales_order'),
                ['fraud_score']
            ),
            ['fraud_score']
        );

        $installer->getConnection()->modifyColumn(
            $installer->getTable('sales_order_grid'),
            'fraud_score',
            [
                'type'     => Table::TYPE_INTEGER,
                'nullable' => true,
                'comment'  => 'Fraud Check Score Calculation',
            ]
        );
        $installer->getConnection()->addIndex(
            $installer->getTable('sales_order_grid'),
            $installer->getIdxName(
                $installer->getTable('sales_order'),
                ['fraud_score']
            ),
            ['fraud_score']
        );
    }
}
