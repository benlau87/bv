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

class OrderActions extends Column
{
    const URL_PATH_VIEW_ORDER   = 'sales/order/view';
    const URL_PATH_SET_STATUS   = 'rentalsystem/order/setStatus';
    const URL_PATH_SEND_RECEIPT = 'rentalsystem/order/sendReceipt';

    /**
     * URL builder
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
     * @param array $dataSource
     *
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as & $item) {
                if (isset($item['order_id'])) {
                    $viewUrlPath                  = self::URL_PATH_VIEW_ORDER;
                    $urlEntityParamName           = 'order_id';
                    $item[$this->getData('name')] = [
                        'view' => [
                            'href'  => $this->_urlBuilder->getUrl(
                                $viewUrlPath,
                                [
                                    $urlEntityParamName => $item['order_id']
                                ]
                            ),
                            'label' => __('View Order')
                        ]
                    ];

                    $item[$this->getData('name')]['set_delivered'] = [
                        'href'   => $this->_urlBuilder->getUrl(
                            self::URL_PATH_SEND_RECEIPT,
                            [
                                'id'     => $item['id'],
                            ]
                        ),
                        'label'  => __('Resend Receipt'),
                        'hidden' => false,
                    ];

                    if (isset($item['status'])) {
                        if ($item['status'] == "<span class=\"grid-severity-critical\">Pending</span>")
                            $item[$this->getData('name')]['set_delivered'] = [
                                'href'   => $this->_urlBuilder->getUrl(
                                    self::URL_PATH_SET_STATUS,
                                    [
                                        'id'     => $item['id'],
                                        'status' => 1
                                    ]
                                ),
                                'label'  => __('Set as Delivered'),
                                'hidden' => false,
                            ];

                        if ($item['status'] == "<span class=\"grid-severity-minor\">Delivered</span>")
                            $item[$this->getData('name')]['set_returned'] = [
                                'href'   => $this->_urlBuilder->getUrl(
                                    self::URL_PATH_SET_STATUS,
                                    [
                                        'id'     => $item['id'],
                                        'status' => 2
                                    ]
                                ),
                                'label'  => __('Set as Returned'),
                                'hidden' => false,
                            ];
                    }
                }
            }
        }

        return $dataSource;
    }
}