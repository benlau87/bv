<?php
namespace Rokanthemes\Featuredpro\Setup;

use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Eav\Setup\EavSetup;
/**
 * @codeCoverageIgnore
 */
class InstallData implements InstallDataInterface{
	/**
	 * {@inheritdoc}
	 */
    /**
     * EAV setup factory
     *
     * @var EavSetupFactory
     */
    private $eavSetupFactory;

    /**
     * Init
     *
     * @param EavSetupFactory $eavSetupFactory
     */
    public function __construct(EavSetupFactory $eavSetupFactory)
    {
        $this->eavSetupFactory = $eavSetupFactory;
    }
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        /** @var EavSetup $eavSetup */
        $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);
        $installer = $setup;

        $installer->startSetup();
        /**
         * Install eav entity types to the eav/entity_type table
         */
        $eavSetup->addAttribute(
            'catalog_product',
            'featured',
            [
                'type' => 'varchar',
                'label' => 'Featured',
                'input' => 'select',
                'source' => 'Magento\Eav\Model\Entity\Attribute\Source\Boolean',
                'required' => false,
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
                'visible' => true,
                'user_defined' => false,
                'searchable' => false,
                'filterable' => false,
                'comparable' => false,
                'visible_on_front' => false,
                'unique' => false,
                'default' => 1,
                'apply_to' => 'simple,configurable,bundle,virtual,downloadable',
                'group' => 'Product Details',
                'is_used_in_grid' => false,
                'is_visible_in_grid' => false,
                'is_filterable_in_grid' => true,
                'used_in_product_listing' => true,
            ]
        );
        
        $installer->endSetup();
    }
}
