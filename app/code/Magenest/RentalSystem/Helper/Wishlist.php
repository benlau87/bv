<?php
/**
 * Copyright © 2019 Magenest. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magenest\RentalSystem\Helper;

use Magento\Wishlist\Helper\Data;

/**
 * Class Wishlist
 * @package Magenest\RentalSystem\Helper
 */
class Wishlist extends Data
{
    /**
     * @param \Magento\Catalog\Model\Product|\Magento\Wishlist\Model\Item $item
     *
     * @return string
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getConfigureUrl($item)
    {
        $productType = $item->getProduct()->getTypeId();
        if ($productType == 'rental' || $productType == 'ticket')
            return $item->getProductUrl();

        return $this->_getUrl(
            'wishlist/index/configure',
            [
                'id' => $item->getWishlistItemId(),
                'product_id' => $item->getProductId()
            ]
        );
    }
}