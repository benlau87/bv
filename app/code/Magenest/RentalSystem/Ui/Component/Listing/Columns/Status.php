<?php
/**
 * Copyright © 2019 Magenest. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magenest\RentalSystem\Ui\Component\Listing\Columns;

use Magento\Ui\Component\Listing\Columns\Column;

class Status extends Column
{
    /**
     * @param array $dataSource
     *
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as & $item) {
                $status = $item['status'];

                if ($status == 0) {
                    $message = __('Pending');
                    $class   = 'grid-severity-critical';
                } else if ($status == 1) {
                    $message = __('Delivered');
                    $class   = 'grid-severity-minor';
                } else {
                    $message = __('Returned');
                    $class   = 'grid-severity-notice';
                }

                $item['status'] = '<span class="' . $class . '">' . $message . '</span>';
            }
        }
        return $dataSource;
    }
}