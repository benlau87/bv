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
class Rental extends AbstractModel
{
    /**
     * Product Type
     */
    const PRODUCT_TYPE = 'rental';

    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Magenest\RentalSystem\Model\ResourceModel\Rental');
    }

    /**
     * @param $id
     *
     * @return Rental
     */
    public function loadByProductId($id)
    {
        return $this->load($id, 'product_id');
    }

    /**
     * @param $id
     *
     * @return string
     */
    public function getEmailTemplate($id)
    {
        return $this->load($id)->getData('email_template');
    }
}