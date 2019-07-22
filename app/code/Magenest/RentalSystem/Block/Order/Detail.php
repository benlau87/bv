<?php
/**
 * Created by PhpStorm.
 * User: ducanh
 * Date: 30/01/2019
 * Time: 13:44
 */
namespace Magenest\RentalSystem\Block\Order;

use Magento\Framework\View\Element\Template;

class Detail extends \Magento\Framework\View\Element\Template
{
    public function __construct(Template\Context $context, array $data = [])
    {
        parent::__construct($context, $data);
    }
}