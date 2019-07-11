<?php
/**
 * Copyright © 2019 Magenest. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magenest\RentalSystem\Controller\Adminhtml;

use Magenest\RentalSystem\Model\RentalOrderFactory;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\App\Response\Http\FileFactory;
use Magento\Ui\Component\MassAction\Filter;
use Magento\Backend\Model\View\Result\Page;
use Magenest\RentalSystem\Helper\Rental as RentalHelper;
use Magenest\RentalSystem\Model\ResourceModel\RentalOrder\CollectionFactory as OrderCollectionFactory;
use Magento\Sales\Model\Order\ItemFactory as OrderItem;

abstract class Order extends Action
{
    /**
     * @var RentalOrderFactory
     */
    protected $_rentalOrderFactory;

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
     * @var OrderCollectionFactory
     */
    protected $_collectionFactory;

    /**
     * @var RentalHelper
     */
    protected $_rentalHelper;

    /**
     * @var OrderItem
     */
    protected $_orderItem;

    /**
     * Order constructor.
     *
     * @param Context $context
     * @param RentalOrderFactory $rentalOrderFactory
     * @param Registry $coreRegistry
     * @param PageFactory $resultPageFactory
     * @param FileFactory $fileFactory
     * @param Filter $filter
     * @param RentalHelper $_rentalHelper
     * @param OrderCollectionFactory $collectionFactory
     * @param OrderItem $orderItem
     */
    public function __construct(
        Context $context,
        RentalOrderFactory $rentalOrderFactory,
        Registry $coreRegistry,
        PageFactory $resultPageFactory,
        FileFactory $fileFactory,
        Filter $filter,
        RentalHelper $_rentalHelper,
        OrderCollectionFactory $collectionFactory,
        OrderItem $orderItem
    ) {
        parent::__construct($context);
        $this->_rentalOrderFactory = $rentalOrderFactory;
        $this->_coreRegistry       = $coreRegistry;
        $this->_resultPageFactory  = $resultPageFactory;
        $this->_fileFactory        = $fileFactory;
        $this->_collectionFactory  = $collectionFactory;
        $this->_filter             = $filter;
        $this->_rentalHelper       = $_rentalHelper;
        $this->_orderItem          = $orderItem;
    }

    /**
     * instantiate result page object
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
     * @return $this
     */
    protected function _setPageData()
    {
        $resultPage = $this->getResultPage();
        $resultPage->setActiveMenu('Magenest_RentalSystem::orders');
        $resultPage->getConfig()->getTitle()->prepend((__('Manage Rental Orders')));

        return $this;
    }

    /**
     * Check ACL
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Magenest_RentalSystem::orders');
    }
}