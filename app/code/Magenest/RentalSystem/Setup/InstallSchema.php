<?php
/**
 * Copyright © 2019 Magenest. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magenest\RentalSystem\Setup;

use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class InstallSchema Implements InstallSchemaInterface
{
    /**
     * install tables
     *
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     *
     * @return void
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     * @throws \Zend_Db_Exception
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();
        if (!$installer->tableExists('magenest_rental_product')) {
            $table = $installer->getConnection()->newTable(
                $installer->getTable('magenest_rental_product')
            )->addColumn(
                'id',
                Table::TYPE_INTEGER,
                null,
                [
                    'identity' => true,
                    'nullable' => false,
                    'primary'  => true,
                    'unsigned' => true,
                ],
                'ID'
            )->addColumn(
                'product_id',
                Table::TYPE_INTEGER,
                10,
                [
                    'nullable' => false,
                    'unsigned' => true,
                ],
                'Product Id'
            )->addColumn(
                'product_name',
                Table::TYPE_TEXT,
                100,
                ['nullable' => true],
                'Product Name'
            )->addColumn(
                'email_template',
                Table::TYPE_TEXT,
                255,
                ['nullable' => true],
                'Receipt Email Template'
            )->addColumn(
                'type',
                Table::TYPE_SMALLINT,
                2,
                [
                    'nullable' => true,
                    'default'  => 0
                ],
                'Delivery Type'
            )->addColumn(
                'lead_time',
                Table::TYPE_SMALLINT,
                2,
                [
                    'nullable' => true,
                    'default'  => 0
                ],
                'Lead Time'
            )->addColumn(
                'max_duration',
                Table::TYPE_INTEGER,
                10,
                [
                    'nullable' => true,
                    'default'  => 0
                ],
                'Max Duration'
            )->addColumn(
                'created_at',
                Table::TYPE_TIMESTAMP,
                null,
                [
                    'nullable' => true,
                    'default'  => Table::TIMESTAMP_INIT
                ],
                'Created At'
            )->addColumn(
                'updated_at',
                Table::TYPE_TIMESTAMP,
                null,
                [
                    'nullable' => true,
                    'default'  => Table::TIMESTAMP_INIT_UPDATE
                ],
                'Updated At'
            )->addColumn(
                'initial_qty',
                Table::TYPE_INTEGER,
                11,
                [
                    'nullable' => true,
                ],
                'Initial Qty'
            )->addColumn(
                'available_qty',
                Table::TYPE_INTEGER,
                11,
                [
                    'nullable' => true,
                ],
                'Available Qty'
            )->addColumn(
                'pickup_address',
                Table::TYPE_TEXT,
                null,
                [
                    'nullable' => true,
                ],
                'Pickup Location'
            )->addColumn(
                'time_rented',
                Table::TYPE_INTEGER,
                null,
                [
                    'nullable' => true,
                    'default'  => 0
                ],
                'Time rented'
            )->setComment('Rental Product Table');
            $installer->getConnection()->createTable($table);

            $installer->getConnection()->addForeignKey(
                $installer->getFkName(
                    $installer->getTable('magenest_rental_product'),
                    'product_id',
                    $installer->getTable('catalog_product_entity'),
                    'entity_id'
                ),
                $installer->getTable('magenest_rental_product'),
                'product_id',
                $installer->getTable('catalog_product_entity'),
                'entity_id',
                Table::ACTION_CASCADE
            );
        }

        if (!$installer->tableExists('magenest_rental_option')) {
            $table = $installer->getConnection()->newTable(
                $installer->getTable('magenest_rental_option')
            )->addColumn(
                'id',
                Table::TYPE_INTEGER,
                null,
                [
                    'identity' => true,
                    'nullable' => false,
                    'primary'  => true,
                    'unsigned' => true,
                ],
                'ID'
            )->addColumn(
                'rental_id',
                Table::TYPE_INTEGER,
                10,
                [
                    'nullable' => false,
                    'unsigned' => true,
                ],
                'Rental Id'
            )->addColumn(
                'product_id',
                Table::TYPE_INTEGER,
                10,
                [
                    'nullable' => false,
                    'unsigned' => true,
                ],
                'Product Id'
            )->addColumn(
                'option_id',
                Table::TYPE_INTEGER,
                10,
                ['nullable' => true],
                'Option Number'
            )->addColumn(
                'option_title',
                Table::TYPE_TEXT,
                100,
                ['nullable' => true],
                'Option Title'
            )->addColumn(
                'type',
                Table::TYPE_TEXT,
                10,
                ['nullable' => true],
                'Price Input Type'
            )->addColumn(
                'is_required',
                Table::TYPE_SMALLINT,
                2,
                [
                    'nullable' => true,
                    'default'  => 0
                ],
                'Is Required'
            )->setComment('Rental Option Table');
            $installer->getConnection()->createTable($table);
        }

        if (!$installer->tableExists('magenest_rental_optiontype')) {
            $table = $installer->getConnection()->newTable(
                $installer->getTable('magenest_rental_optiontype')
            )->addColumn(
                'id',
                Table::TYPE_INTEGER,
                null,
                [
                    'identity' => true,
                    'nullable' => false,
                    'primary'  => true,
                    'unsigned' => true,
                ],
                'ID'
            )->addColumn(
                'option_id',
                Table::TYPE_INTEGER,
                10,
                [
                    'nullable' => false,
                    'unsigned' => true,
                ],
                'Option Id'
            )->addColumn(
                'product_id',
                Table::TYPE_INTEGER,
                10,
                [
                    'nullable' => false,
                    'unsigned' => true,
                ],
                'Product Id'
            )->addColumn(
                'option_number',
                Table::TYPE_INTEGER,
                10,
                ['nullable' => true],
                'Option Type Number'
            )->addColumn(
                'option_title',
                Table::TYPE_TEXT,
                100,
                ['nullable' => true],
                'Option Name'
            )->addColumn(
                'price',
                Table::TYPE_DECIMAL,
                '12,2',
                [
                    'nullable' => false,
                    'default'  => '0.00'
                ],
                'Option Price'
            )->addColumn(
                'period',
                Table::TYPE_SMALLINT,
                2,
                [
                    'nullable' => true,
                    'default'  => 0
                ],
                'Price Per Period'
            )->setComment('Rental Option Type Table');
            $installer->getConnection()->createTable($table);
        }

        if (!$installer->tableExists('magenest_rental_order')) {
            $table = $installer->getConnection()->newTable(
                $installer->getTable('magenest_rental_order')
            )->addColumn(
                'id',
                Table::TYPE_INTEGER,
                null,
                [
                    'identity' => true,
                    'nullable' => false,
                    'primary'  => true,
                    'unsigned' => true,
                ],
                'Rental Order ID'
            )->addColumn(
                'order_increment_id',
                Table::TYPE_TEXT,
                12,
                ['nullable' => false],
                'Order Increment Id'
            )->addColumn(
                'order_item_id',
                Table::TYPE_INTEGER,
                10,
                ['nullable' => false],
                'Order Item ID'
            )->addColumn(
                'price',
                Table::TYPE_DECIMAL,
                '12,2',
                [
                    'nullable' => false,
                    'default'  => '0.00'
                ],
                'Order Item Total'
            )->addColumn(
                'qty',
                Table::TYPE_INTEGER,
                10,
                ['nullable' => false],
                'Quantity'
            )->addColumn(
                'return_qty',
                Table::TYPE_INTEGER,
                10,
                [
                    'nullable' => true,
                    'default'  => null
                ],
                'Return to stock qty'
            )->addColumn(
                'start_time',
                Table::TYPE_DATETIME,
                null,
                [],
                'Start Time'
            )->addColumn(
                'end_time',
                Table::TYPE_DATETIME,
                null,
                [],
                'End Time'
            )->addColumn(
                'created_at',
                Table::TYPE_TIMESTAMP,
                null,
                [
                    'nullable' => false,
                    'default'  => Table::TIMESTAMP_INIT
                ],
                'Created At'
            )->addColumn(
                'updated_at',
                Table::TYPE_TIMESTAMP,
                null,
                [
                    'default'  => Table::TIMESTAMP_INIT_UPDATE
                ],
                'Updated At'
            )->addColumn(
                'status',
                Table::TYPE_SMALLINT,
                2,
                [
                    'nullable' => false,
                    'default'  => 0
                ],
                'Status'
            )->addColumn(
                'rental_id',
                Table::TYPE_INTEGER,
                10,
                [
                    'nullable' => true,
                ],
                'Rental Product ID'
            )->addColumn(
                'title',
                Table::TYPE_TEXT,
                255,
                [
                    'nullable' => true,
                ],
                'Product Name'
            )->addColumn(
                'note',
                Table::TYPE_TEXT,
                255,
                [
                    'nullable' => true,
                ],
                'Additional Options'
            )->addColumn(
                'information',
                Table::TYPE_TEXT,
                255,
                [
                    'nullable' => true,
                ],
                'Rental Information'
            )->addColumn(
                'customer_id',
                Table::TYPE_INTEGER,
                10,
                [
                    'nullable' => true,
                ],
                'Customer ID'
            )->addColumn(
                'customer_name',
                Table::TYPE_TEXT,
                255,
                [
                    'nullable' => true,
                ],
                'Customer Name'
            )->addColumn(
                'customer_email',
                Table::TYPE_TEXT,
                255,
                [
                    'nullable' => true,
                ],
                'Customer Email'
            )->addColumn(
                'customer_address',
                Table::TYPE_TEXT,
                255,
                [
                    'nullable' => true,
                ],
                'Customer Address'
            )->addColumn(
                'code',
                Table::TYPE_TEXT,
                255,
                [],
                'Code'
            )->addColumn(
                'type',
                Table::TYPE_SMALLINT,
                null,
                [],
                'Delivery Type'
            )->addColumn(
                'order_id',
                Table::TYPE_INTEGER,
                10,
                [
                    'nullable' => false,
                ],
                'Order ID'
            )->setComment('Rental Order Table');

            $installer->getConnection()->createTable($table);
        }

        if (!$installer->tableExists('magenest_rental_price')) {
            $table = $installer->getConnection()->newTable(
                $installer->getTable('magenest_rental_price')
            )->addColumn(
                'id',
                Table::TYPE_INTEGER,
                null,
                [
                    'identity' => true,
                    'nullable' => false,
                    'primary'  => true,
                    'unsigned' => true,
                ],
                'ID'
            )->addColumn(
                'rental_id',
                Table::TYPE_INTEGER,
                10,
                [
                    'nullable' => false,
                    'unsigned' => true,
                ],
                'Rental Product Id'
            )->addColumn(
                'product_id',
                Table::TYPE_INTEGER,
                10,
                [
                    'nullable' => false,
                    'unsigned' => true,
                ],
                'Product Id'
            )->addColumn(
                'type',
                Table::TYPE_SMALLINT,
                2,
                [
                    'nullable' => false,
                    'default'  => 0
                ],
                'Price Type'
            )->addColumn(
                'base_price',
                Table::TYPE_DECIMAL,
                '12,2',
                [
                    'nullable' => false,
                    'default'  => '0.00'
                ],
                'Base Price'
            )->addColumn(
                'base_period',
                Table::TYPE_TEXT,
                10,
                [
                    'nullable' => false,
                    'default'  => '1d'
                ],
                'Base Period'
            )->addColumn(
                'additional_price',
                Table::TYPE_DECIMAL,
                '12,2',
                ['nullable' => true],
                'Additional Price'
            )->addColumn(
                'additional_period',
                Table::TYPE_TEXT,
                10,
                ['nullable' => true],
                'Additional Period'
            )->setComment('Rental Price Table');
            $installer->getConnection()->createTable($table);
        }
    }
}