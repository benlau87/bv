<?php
/**
 *
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Rokanthemes\QuickView\Controller\Product;

use Rokanthemes\QuickView\Controller\Product as ProductController;
use Magento\Framework\Controller\ResultFactory;

class Quickview extends ProductController
{
    public function execute()
    {
        $pr = $this->initProduct();
        /** @var \Magento\Framework\View\Result\Layout $resultLayout */
        $resultLayout = $this->resultFactory->create(ResultFactory::TYPE_LAYOUT);
		$resultLayout->addHandle('catalog_product_view_type_'. $pr->getTypeId());
        return $resultLayout;
    }
}
