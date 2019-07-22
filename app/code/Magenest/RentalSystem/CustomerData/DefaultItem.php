<?php
/**
 * Copyright © 2019 Magenest. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magenest\RentalSystem\CustomerData;


class DefaultItem extends \Magento\Checkout\CustomerData\DefaultItem
{
    /**
     * DefaultItem constructor.
     * @param \Magento\Catalog\Helper\Image $imageHelper
     * @param \Magento\Msrp\Helper\Data $msrpHelper
     * @param \Magento\Framework\UrlInterface $urlBuilder
     * @param \Magento\Catalog\Helper\Product\ConfigurationPool $configurationPool
     * @param \Magento\Checkout\Helper\Data $checkoutHelper
     */
    public function __construct(
        \Magento\Catalog\Helper\Image $imageHelper,
        \Magento\Msrp\Helper\Data $msrpHelper,
        \Magento\Framework\UrlInterface $urlBuilder,
        \Magento\Catalog\Helper\Product\ConfigurationPool $configurationPool,
        \Magento\Checkout\Helper\Data $checkoutHelper
    ) {
        parent::__construct($imageHelper, $msrpHelper, $urlBuilder, $configurationPool, $checkoutHelper);
    }

    /**
     * Get item configure url
     *
     * @return string
     */
    protected function getConfigureUrl()
    {
        $productType = $this->getProduct()->getTypeId();
        $link = $this->urlBuilder->getUrl(
            'checkout/cart/configure',
            ['id' => $this->item->getId(), 'product_id' => $this->item->getProduct()->getId()]
        );
        if ($productType == 'ticket' || $productType == 'rental') {
            $link = $this->urlBuilder->getUrl(
                'catalog/product/view',
                ['id' => $this->item->getProduct()->getId()]
            );
        }

        return $link;
    }
}