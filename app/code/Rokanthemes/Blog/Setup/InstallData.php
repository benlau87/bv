<?php
/**
 * Copyright © 2015 RokanThemes.com. All rights reserved.

 * @author RokanThemes Team <contact@rokanthemes.com>
 */

namespace Rokanthemes\Blog\Setup;

use Rokanthemes\Blog\Model\Post;
use Rokanthemes\Blog\Model\PostFactory;
use Magento\Framework\Module\Setup\Migration;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

/**
 * @codeCoverageIgnore
 */
class InstallData implements InstallDataInterface
{
    /**
     * Post factory
     *
     * @var \Rokanthemes\Blog\Model\PostFactory
     */
    private $_postFactory;

    /**
     * Init
     *
     * @param \Rokanthemes\Blog\Model\PostFactory $postFactory
     */
    public function __construct(\Rokanthemes\Blog\Model\PostFactory $postFactory)
    {
        $this->_postFactory = $postFactory;
    }

    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $data = [
            'title' => 'Hello world!',
            'meta_keywords' => 'magento 2 blog',
            'meta_description' => 'Magento 2 blog default post.',
            'identifier' => 'hello-world',
            'content_heading' => 'Hello world!',
            'content' => 'Welcome to <a target="_blank" href="http://www.rokanthemes.com/" title="rokanthemes - solutions for Magento 2">rokanthemes</a> blog extension for Magento&reg; 2. This is your first post. Edit or delete it, then start blogging!',
            'stores' => [0]
        ];

        $this->_postFactory->create()->setData($data)->save();
    }

}
