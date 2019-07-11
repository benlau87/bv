<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_Orderexport
 */

namespace Amasty\Orderexport\Setup;


use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;


class UpgradeSchema implements UpgradeSchemaInterface
{
    /**
     * {@inheritdoc}
     */
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        if (version_compare($context->getVersion(), '1.1.0', '<')) {
            $setup->getConnection()->addColumn(
                $setup->getTable('amasty_amorderexport_profiles'),
                'split_order_items',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                    'nullable' => true,
                    'default' => '1',
                    'after' => 'export_include_fieldnames',
                    'comment' => 'Split Order Items'
                ]
            );

            $setup->getConnection()->addColumn(
                $setup->getTable('amasty_amorderexport_profiles'),
                'split_order_items_delim',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'nullable' => true,
                    'default' => ',',
                    'length' => 12,
                    'after' => 'split_order_items',
                    'comment' => 'Split Order Items Delim'
                ]
            );

            $setup->getConnection()->addColumn(
                $setup->getTable('amasty_amorderexport_profiles'),
                'xml_main_tag',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'nullable' => true,
                    'default' => 'orders',
                    'length' => 255,
                    'after' => 'split_order_items_delim',
                    'comment' => 'Xml Main Tag'
                ]
            );

            $setup->getConnection()->addColumn(
                $setup->getTable('amasty_amorderexport_profiles'),
                'xml_order_tag',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'nullable' => true,
                    'default' => 'order',
                    'length' => 255,
                    'after' => 'xml_main_tag',
                    'comment' => 'Xml Order Tag'
                ]
            );

            $setup->getConnection()->addColumn(
                $setup->getTable('amasty_amorderexport_profiles'),
                'xml_order_items_tag',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'nullable' => true,
                    'default' => 'order_items',
                    'length' => 255,
                    'after' => 'xml_order_tag',
                    'comment' => 'Xml Main Tag'
                ]
            );

            $setup->getConnection()->addColumn(
                $setup->getTable('amasty_amorderexport_profiles'),
                'xml_order_item_tag',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'nullable' => true,
                    'default' => 'order_item',
                    'length' => 255,
                    'after' => 'xml_order_items_tag',
                    'comment' => 'Xml Order item Tag'
                ]
            );
        }

        if (version_compare($context->getVersion(), '1.1.1', '<')) {
            $setup->getConnection()->addColumn(
                $setup->getTable('amasty_amorderexport_profiles'),
                'skip_child_products',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                    'nullable' => false,
                    'default' => '1',
                    'comment' => 'Skip Child Products'
                ]
            );
        }

        if (version_compare($context->getVersion(), '1.1.2', '<')) {
            $setup->getConnection()->addColumn(
                $setup->getTable('amasty_amorderexport_profiles'),
                'email_from',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'nullable' => false,
                    'default' => NULL,
                    'length' => 255,
                    'after' => 'email_use',
                    'comment' => 'Email From path'
                ]
            );
        }

        if (version_compare($context->getVersion(), '1.2.0', '<')) {
            $this->installAttributesSchema($setup, $context);
        }

        if (version_compare($context->getVersion(), '1.2.1', '<')) {
            $setup->getConnection()->addColumn(
                $setup->getTable('amasty_amorderexport_profiles'),
                'skip_parent_products',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                    'nullable' => false,
                    'default' => '0',
                    'comment' => 'Skip Parent Products'
                ]
            );
        }

        if ($context->getVersion() && version_compare($context->getVersion(), '1.3.0', '<')) {
            $setup->getConnection()->addColumn(
                $setup->getTable('amasty_amorderexport_profiles'),
                'static_fields',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'nullable' => false,
                    'comment' => 'Static fields'
                ]
            );
        }

        if ($context->getVersion() && version_compare($context->getVersion(), '1.3.2', '<')) {
            $setup->getConnection()->changeColumn(
                $setup->getTable('amasty_amorderexport_history'),
                'last_increment_id',
                'last_increment_id',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'length' => 50,
                    'comment' => 'Last Increment ID of Order'
                ]
            );

            $setup->getConnection()->changeColumn(
                $setup->getTable('amasty_amorderexport_history'),
                'last_invoice_increment_id',
                'last_invoice_increment_id',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'length' => 50,
                    'comment' => 'Last Increment ID of Invoice'
                ]
            );
        }

        $setup->endSetup();
    }

    public function installAttributesSchema(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;

        $table = $installer->getConnection()->newTable(
            $installer->getTable('amasty_amorderexport_attribute')
        )->addColumn(
            'entity_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
            null,
            ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
            'Entity ID'
        )->addColumn(
            'attribute_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
            null,
            ['unsigned' => true, 'nullable' => true, 'default' => '0'],
            'Attribute Id'
        )->addColumn(
            'attribute_code',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            ['nullable' => true, 'default' => null],
            'Attribute Code'
        )->addColumn(
            'frontend_label',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            [],
            'Frontend Label'
        )->addIndex(
            $installer->getIdxName(
                'amasty_amorderexport_attribute',
                ['attribute_id'],
                \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_UNIQUE
            ),
            ['attribute_id'],
            ['type' => \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_UNIQUE]
        )->addForeignKey(
            $installer->getFkName('amasty_amorderexport_attribute', 'attribute_id', 'eav_attribute', 'attribute_id'),
            'attribute_id',
            $installer->getTable('eav_attribute'),
            'attribute_id',
            \Magento\Framework\DB\Ddl\Table::ACTION_SET_NULL
        )->setComment(
            'Amasty Order Export Attribute'
        );

        $installer->getConnection()->createTable($table);

        $table = $installer->getConnection()->newTable(
            $installer->getTable('amasty_amorderexport_attribute_index')
        )->addColumn(
            'entity_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            10,
            ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
            'Entity ID'
        )->addColumn(
            'order_item_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['unsigned' => true, 'nullable' => false],
            'Order Item Id'
        )->addIndex(
            $installer->getIdxName(
                'amasty_amorderexport_attribute_index',
                ['order_item_id'],
                \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_UNIQUE
            ),
            ['order_item_id'],
            ['type' => \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_UNIQUE]
        )->addForeignKey(
            $installer->getFkName('amasty_amorderexport_attribute_index', 'order_item_id', 'sales_order_item', 'item_id'),
            'order_item_id',
            $installer->getTable('sales_order_item'),
            'item_id',
            \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
        )->setComment(
            'Amasty Order Export Attribute Index'
        );

        $installer->getConnection()->createTable($table);
    }
}
