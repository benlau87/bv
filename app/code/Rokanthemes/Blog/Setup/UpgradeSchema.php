<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Rokanthemes\Blog\Setup;

use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

/**
 * Upgrade the Catalog module DB scheme
 */
class UpgradeSchema implements UpgradeSchemaInterface
{
    /**
     * {@inheritdoc}
     */
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        if (version_compare($context->getVersion(), '2.0.1', '<')) {
            // Get module table
            $tableName = $setup->getTable('rokanthemes_blog_post');

            // Check if the table already exists
            if ($setup->getConnection()->isTableExists($tableName) == true) {
                // Declare data
                $columns = [
                    'detailimage' => [
                        'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        'nullable' => false,
                        'comment' => 'Detail Image',
                    ],
                ];

                $connection = $setup->getConnection();
                foreach ($columns as $name => $definition) {
                    $connection->addColumn($tableName, $name, $definition);
                }
            }
        }
		
        $setup->endSetup();
    }
}
