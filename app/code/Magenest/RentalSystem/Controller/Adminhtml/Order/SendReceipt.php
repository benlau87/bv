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

class SendReceipt extends Action
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
     * SetStatus constructor.
     *
     * @param Context $context
     * @param RentalOrderFactory $rentalOrderFactory
     * @param Helper $helper
     */
    public function __construct(
        Context $context,
        RentalOrderFactory $rentalOrderFactory,
        Helper $helper
    ) {
        parent::__construct($context);
        $this->_rentalOrderFactory = $rentalOrderFactory;
        $this->_helper             = $helper;
    }

    /**
     * @return \Magento\Backend\Model\View\Result\Redirect|\Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function execute()
    {
        $params = $this->getRequest()->getParams();
        $id     = $params['id'];
        $model  = $this->_rentalOrderFactory->create()->load($id);

        $data = $model->getData();
        $this->_helper->sendReceipt($data);

        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);

        $this->messageManager->addSuccessMessage(__('Resent receipt for rental order ID:%1', $id));

        return $resultRedirect->setPath('*/*/');
    }
}