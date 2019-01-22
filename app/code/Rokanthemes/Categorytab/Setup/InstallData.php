<?php

namespace Rokanthemes\Categorytab\Setup;

use Magento\Framework\Module\Setup\Migration;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Catalog\Setup\CategorySetupFactory;

/**
 * @codeCoverageIgnore
 */
class InstallData implements InstallDataInterface
{
    /**
     * Category setup factory
     *
     * @var CategorySetupFactory
     */
    private $categorySetupFactory;
 
    /**
     * Init
     *
     * @param CategorySetupFactory $categorySetupFactory
     */
    public function __construct(CategorySetupFactory $categorySetupFactory)
    {
        $this->categorySetupFactory = $categorySetupFactory;
    }
    
    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        
        $installer->startSetup();
        
        $categorySetup = $this->categorySetupFactory->create(['setup' => $setup]);
        $entityTypeId = $categorySetup->getEntityTypeId(\Magento\Catalog\Model\Category::ENTITY);
        $attributeSetId = $categorySetup->getDefaultAttributeSetId($entityTypeId);
        
        $menu_attributes = [
            'cat_image_thumbnail' => [
                'type' => 'varchar',
                'label' => 'Image',
                'input' => 'image',
                'backend' => 'Rokanthemes\Categorytab\Model\Category\Attribute\Backend\Thumbnailimage',
                'required' => false,
                'sort_order' => 70,
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                'group' => 'Thumbnail Image'
            ]
        ];
        
        foreach($menu_attributes as $item => $data) {
            $categorySetup->addAttribute(\Magento\Catalog\Model\Category::ENTITY, $item, $data);
        }
        
        $idg =  $categorySetup->getAttributeGroupId($entityTypeId, $attributeSetId, 'Thumbnail Image');
        
        foreach($menu_attributes as $item => $data) {
            $categorySetup->addAttributeToGroup(
                $entityTypeId,
                $attributeSetId,
                $idg,
                $item,
                $data['sort_order']
            );
        }

        $installer->endSetup();
    }
}