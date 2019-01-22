<?php

namespace Rokanthemes\LayeredAjax\Model\Search;

use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\ObjectFactory;
use Magento\Framework\Api\Search\FilterGroupBuilder as SourceFilterGroupBuilder;

class FilterGroupBuilder extends SourceFilterGroupBuilder
{
	
	public function __construct(
		ObjectFactory $objectFactory,
		FilterBuilder $filterBuilder
	)
	{
		parent::__construct($objectFactory, $filterBuilder);
	}

	public function setFilterBuilder($filterBuilder)
	{
		$this->_filterBuilder = $filterBuilder;
	}

	public function cloneObject()
	{
		$cloneObject = clone $this;
		$cloneObject->setFilterBuilder(clone $this->_filterBuilder);

		return $cloneObject;
	}

	public function removeFilter($attributeCode)
	{
		if (isset($this->data[FilterGroup::FILTERS])) {
			foreach ($this->data[FilterGroup::FILTERS] as $key => $filter) {
				if ($filter->getField() == $attributeCode) {
					unset($this->data[FilterGroup::FILTERS][$key]);
				}
			}
		}

		return $this;
	}
}
