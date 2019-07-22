<?php
/**
 * Copyright © 2019 Magenest. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magenest\RentalSystem\Ui\Component\Listing\Columns;

use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Ui\Component\Listing\Columns\Column;

class ProductActions extends Column
{
    /**
     * Url path  to edit
     *
     * @var string
     */
    const URL_PATH_EDIT = 'catalog/product/edit';

    /**
     * URL builder
     *
     * @var UrlInterface
     */
    protected $_urlBuilder;

    /**
     * constructor
     *
     * @param UrlInterface $urlBuilder
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param array $components
     * @param array $data
     */
    public function __construct(
        UrlInterface $urlBuilder,
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        array $components = [],
        array $data = []
    ) {
        $this->_urlBuilder = $urlBuilder;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as &$item) {
                if (isset($item['product_id'])) {
                    $item[$this->getData('name')]['edit'] = [
                        'href' => $this->_urlBuilder->getUrl(
                            static::URL_PATH_EDIT,
                            [
                                'id' => @$item['product_id']
                            ]
                        ),
                        'label' => __('Edit'),
                        'hidden' => false,
                    ];
                }
            }
        }

        return $dataSource;
    }
}