<?php
/**
 * Copyright © 2019 Magenest. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magenest\RentalSystem\Controller\Adminhtml\Order;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magenest\RentalSystem\Model\RentalOrderFactory;
use Magenest\RentalSystem\Helper\Rental as Helper;
use Magento\Framework\Controller\ResultFactory;
use Magento\Sales\Model\Order\ItemFactory as OrderItem;

class SetStatus extends Action
{
    /**
     * @var RentalOrderFactory
     */
    protected $_rentalOrderFactory;

    /**
     * @var Helper
     */
    protected $_helper;

    /**
     * @var OrderItem
     */
    protected $_orderItem;

    /**
     * SetStatus constructor.
     *
     * @param Context $context
     * @param RentalOrderFactory $rentalOrderFactory
     * @param Helper $helper
     * @param OrderItem $orderItem
     */
    public function __construct(
        Context $context,
        RentalOrderFactory $rentalOrderFactory,
        Helper $helper,
        OrderItem $orderItem
    ) {
        parent::__construct($context);
        $this->_rentalOrderFactory = $rentalOrderFactory;
        $this->_helper             = $helper;
        $this->_orderItem          = $orderItem;
    }

    /**
     * @return \Magento\Backend\Model\View\Result\Redirect|\Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface
     * @throws \Exception
     */
    public function execute()
    {
        $params = $this->getRequest()->getParams();
        $id     = $params['id'];
        $status = $params['status'];
        $model  = $this->_rentalOrderFactory->create()->load($id);
        $model->setStatus($status)->save();
        if ($status == 2) {
            $rentalProductId = $model->getData('rental_id');
            $qty             = $model->getData('qty');
            $returnQty       = $model->getData('return_qty');
            $returnQty       = isset($returnQty) ? $returnQty : 0;
            $orderItem       = $this->_orderItem->create()->load($model->getData('order_item_id'));
            $this->_helper->updateAvailableQty($model->getData('rental_id'),$orderItem->getQtyRefunded() - $returnQty);
            $this->_helper->updateRentalProductQty($rentalProductId);
        } else
            $this->_helper->updateAvailableQty($model->getData('rental_id'));

        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);

        $this->messageManager->addSuccessMessage(__('Saved status for rental ID:%1', $id));

        return $resultRedirect->setPath('*/*/');
    }
}