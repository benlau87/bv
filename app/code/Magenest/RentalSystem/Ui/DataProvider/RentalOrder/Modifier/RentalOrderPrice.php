<?php
/**
 * Created by PhpStorm.
 * User: ducanh
 * Date: 14/02/2019
 * Time: 14:33
 */

namespace Magenest\RentalSystem\Ui\DataProvider\RentalOrder\Modifier;

use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\Pricing\Helper\Data;

class RentalOrderPrice extends \Magento\Ui\Component\Listing\Columns\Column
{
    protected $helperData;
    const NAME = 'column.price';

    public function __construct(
        Data $helperData,
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        array $components = [],
        array $data = [])
    {
        $this->helperData = $helperData;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            $fieldName = $this->getData('name');
            foreach ($dataSource['data']['items'] as & $item) {
                if (isset($item[$fieldName])) {
                    $item[$fieldName] = $this->helperData->currency($item[$fieldName], true, false);
                }
            }
        }
        return $dataSource;
    }

}
