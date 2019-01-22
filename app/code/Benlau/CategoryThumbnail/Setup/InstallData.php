<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Benlau\CategoryThumbnail\Setup;

use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Catalog\Helper\DefaultCategory;
use Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface;
use Magento\Catalog\Setup\CategorySetupFactory;

/**
 * Upgrade Data script
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
        /** @var \Magento\Catalog\Setup\CategorySetup $categorySetup */
        $categorySetup = $this->categorySetupFactory->create(['setup' => $setup]);
        
        $categorySetup->addAttribute(
            \Magento\Catalog\Model\Category::ENTITY,
            'thumbnail',
            [
                'type' => 'varchar',
                'label' => 'Thumbnail',
                'input' => 'image',
                'backend' => 'Magento\Catalog\Model\Category\Attribute\Backend\Image',
                'required' => false,
                'sort_order' => 5,
                'global' => ScopedAttributeInterface::SCOPE_STORE,
                'group' => 'Content',
                'is_used_in_grid' => false,
                'is_visible_in_grid' => false,
                'is_filterable_in_grid' => false
            ]
        );
    }
}
