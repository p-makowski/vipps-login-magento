<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Vipps\Login\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\DB\Adapter\AdapterInterface;

/**
 * @codeCoverageIgnore
 */
class InstallSchema implements InstallSchemaInterface
{
    /**
     * {@inheritdoc}
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();

        $vippsCustomerEntityTableName = $installer->getConnection()->getTableName('vipps_customer_entity');
        $customerEntityTableName = $installer->getConnection()->getTableName('customer_entity');

        $table = $installer->getConnection()->newTable(
            $installer->getTable('vipps_customer_entity')
        )->addColumn(
            'entity_id',
            Table::TYPE_INTEGER,
            null,
            ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
            'Entity Id'
        )->addColumn(
            'customer_entity_id',
            Table::TYPE_INTEGER,
            null,
            ['unsigned' => true, 'nullable' => false],
            'Customer Entity Id'
        )->addColumn(
            'website_id',
            Table::TYPE_SMALLINT,
            null,
            ['unsigned' => true],
            'Website Id'
        )->addColumn(
            'email',
            Table::TYPE_TEXT,
            255,
            ['nullable' => false],
            'Email'
        )->addColumn(
            'telephone',
            Table::TYPE_TEXT,
            255,
            ['nullable' => false],
            'Vipps Telephone'
        )->addColumn(
            'linked',
            Table::TYPE_SMALLINT,
            null,
            ['unsigned' => true, 'nullable' => false, 'default' => '0'],
            'Is Active'
        )->addIndex(
            $installer->getIdxName($vippsCustomerEntityTableName, ['telephone', 'website_id', 'linked']),
            ['telephone', 'website_id', 'linked']
        )->addIndex(
            $installer->getIdxName(
                'vipps_customer_entity',
                ['customer_entity_id'],
                AdapterInterface::INDEX_TYPE_UNIQUE
            ),
            ['customer_entity_id'],
            ['type' => AdapterInterface::INDEX_TYPE_UNIQUE]
        )->addForeignKey(
            $installer->getFkName(
                $vippsCustomerEntityTableName,
                'customer_entity_id',
                $customerEntityTableName,
                'entity_id'
            ),
            'customer_entity_id',
            $installer->getTable('customer_entity'),
            'entity_id',
            Table::ACTION_CASCADE
        )->setComment(
            'Vipps Customer Entity'
        );
        $installer->getConnection()->createTable($table);

        $installer->endSetup();
    }
}
