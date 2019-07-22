<?php
/**
 * MB "Vienas bitas" (Magetrend.com)
 *
 * PHP version 5.3 or later
 *
 * @category MageTrend
 * @package  Magetend/GiftCard
 * @author   Edvinas Stulpinas <edwin@magetrend.com>
 * @license  http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link     https://www.magetrend.com/magento-2-gift-card
 */

namespace Magetrend\PdfTemplates\Setup;


use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

/**
 * @codeCoverageIgnore
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
            $this->upgrade201($setup, $context);
        }
        $setup->endSetup();
    }

    /**
     * @param SchemaSetupInterface $setup
     * @param $context
     */
    public function upgrade201($setup, $context)
    {
        $db = $setup->getConnection();
        $db->addColumn(
            $setup->getTable('mt_pdftemplates_template'),
            'size',
            [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                'length' => 50,
                'nullable' => false,
                'comment' => 'Paper Size'
            ]
        );
    }
}
