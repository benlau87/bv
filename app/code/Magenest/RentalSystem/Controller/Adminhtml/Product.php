<?php
/**
 * Copyright © 2019 Magenest. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magenest\RentalSystem\Controller\Adminhtml;

use Magenest\RentalSystem\Model\RentalFactory;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\App\Response\Http\FileFactory;
use Magento\Ui\Component\MassAction\Filter;
use Magento\Backend\Model\View\Result\Page;
use Magenest\RentalSystem\Model\ResourceModel\Rental\CollectionFactory as ProductCollectionFactory;

abstract class Product extends Action
{
    /**
     * @var RentalFactory
     */
    protected $_rentalFactory;

    /**
     * Core registry
     * @var Registry
     */
    protected $_coreRegistry;

    /**
     * Page result factory
     * @var PageFactory
     */
    protected $_resultPageFactory;

    /**
     * Page factory
     * @var Page
     */
    protected $_resultPage;

    /**
     * Mass Action Filter
     * @var Filter
     */
    protected $_filter;

    /**
     * @var FileFactory
     */
    protected $_fileFactory;

    /**
     * @var ProductCollectionFactory
     */
    protected $_collectionFactory;

    /**
     * Product constructor.
     *
     * @param Context $context
     * @param RentalFactory $rentalFactory
     * @param Registry $coreRegistry
     * @param PageFactory $resultPageFactory
     * @param FileFactory $fileFactory
     * @param Filter $filter
     * @param ProductCollectionFactory $collectionFactory
     */
    public function __construct(
        Context $context,
        RentalFactory $rentalFactory,
        Registry $coreRegistry,
        PageFactory $resultPageFactory,
        FileFactory $fileFactory,
        Filter $filter,
        ProductCollectionFactory $collectionFactory
    ) {
        $this->_rentalFactory     = $rentalFactory;
        $this->_coreRegistry      = $coreRegistry;
        $this->_resultPageFactory = $resultPageFactory;
        $this->_fileFactory       = $fileFactory;
        $this->_collectionFactory = $collectionFactory;
        $this->_filter            = $filter;
        parent::__construct($context);
    }

    /**
     * instantiate result page object
     *
     * @return \Magento\Backend\Model\View\Result\Page|\Magento\Framework\View\Result\Page
     */
    public function getResultPage()
    {
        if (is_null($this->_resultPage)) {
            $this->_resultPage = $this->_resultPageFactory->create();
        }

        return $this->_resultPage;
    }

    /**
     * set page data
     *
     * @return $this
     */
    protected function _setPageData()
    {
        $resultPage = $this->getResultPage();
        $resultPage->setActiveMenu('Magenest_RentalSystem::products');
        $resultPage->getConfig()->getTitle()->prepend((__('Manage Rental Products')));

        return $this;
    }

    /**
     * Check ACL
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Magenest_RentalSystem::products');
    }
}