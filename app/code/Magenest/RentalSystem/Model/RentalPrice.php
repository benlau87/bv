<?php
/**
 * Copyright © 2019 Magenest. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magenest\RentalSystem\Model;

use Magento\Framework\Model\AbstractModel;

/**
 * Class Registration
 * @package Magenest\RentalSystem\Model
 */
class RentalPrice extends AbstractModel
{
    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Magenest\RentalSystem\Model\ResourceModel\RentalPrice');
    }

    /**
     * @param $id
     *
     * @return RentalPrice
     */
    public function loadByProductId($id)
    {
        return $this->load($id, 'product_id');
    }
}