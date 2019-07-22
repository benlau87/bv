<?php
/**
 * Copyright © 2019 Magenest. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magenest\RentalSystem\Block\Adminhtml\System\Config;

use Magento\Framework\Registry;
use Magento\Backend\Block\Template\Context;
use Magento\Cms\Model\Wysiwyg\Config as WysiwygConfig;
use Magento\Framework\Data\Form\Element\AbstractElement;
use Magento\Config\Block\System\Config\Form\Field;

class Editor extends Field
{
    /**
     * @var  Registry
     */

    protected $_coreRegistry;

    /**
     * @var WysiwygConfig
     */
    protected $_wysiwygConfig;

    /**
     * Editor constructor.
     *
     * @param Context $context
     * @param WysiwygConfig $wysiwygConfig
     * @param array $data
     */
    public function __construct(
        Context $context,
        WysiwygConfig $wysiwygConfig,
        array $data = []
    ) {
        $this->_wysiwygConfig = $wysiwygConfig;
        parent::__construct($context, $data);
    }

    public function _getElementHtml(AbstractElement $element)
    {
        $config = [
            'add_variables' => false,
            'add_widgets' => false,
//            'add_images' => false
        ];
        // set configuration values
        $element->setConfig($this->_wysiwygConfig->getConfig($config));
        return parent::_getElementHtml($element);
    }

}