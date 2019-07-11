<?php
/**
 * Copyright © 2019 Magenest. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magenest\RentalSystem\Controller\Adminhtml\Product;

use Magenest\RentalSystem\Controller\Adminhtml\Product as ProductController;
use Magenest\RentalSystem\Model\RentalFactory;
use Magenest\RentalSystem\Model\ResourceModel\Rental\CollectionFactory as ProductCollectionFactory;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Response\Http\FileFactory;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;
use Magento\Ui\Component\MassAction\Filter;
use Magento\Catalog\Model\ProductFactory;

class MassDelete extends ProductController
{
    /**
     * @var \Magento\Catalog\Model\ProductFactory
     */
    protected $_productFactory;

    /**
     * MassDelete constructor.
     *
     * @param Context $context
     * @param RentalFactory $rentalFactory
     * @param Registry $coreRegistry
     * @param PageFactory $resultPageFactory
     * @param FileFactory $fileFactory
     * @param Filter $filter
     * @param ProductCollectionFactory $collectionFactory
     * @param ProductFactory $_productFactory
     */
    public function __construct(
        Context $context,
        RentalFactory $rentalFactory,
        Registry $coreRegistry,
        PageFactory $resultPageFactory,
        FileFactory $fileFactory,
        Filter $filter,
        ProductCollectionFactory $collectionFactory,
        ProductFactory $_productFactory
    ) {
        $this->_productFactory = $_productFactory;
        parent::__construct($context, $rentalFactory, $coreRegistry, $resultPageFactory, $fileFactory, $filter, $collectionFactory);
    }



    public function execute()
    {
        $collection = $this->_filter->getCollection($this->_collectionFactory->create());
        $delete = 0;
        foreach ($collection as $item) {
            $product = $this->_productFactory->create()->load($item->getProductId());
            if (!empty($product->getId()))
            {
                $product->delete();
            }
            /** @var \Magenest\RentalSystem\Model\Rental $item */
            $item->delete();
            $delete++;
        }
        $this->messageManager->addSuccess(__('A total of %1 product(s) have been deleted.', $delete));
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);

        return $resultRedirect->setPath('*/*/');
    }
}