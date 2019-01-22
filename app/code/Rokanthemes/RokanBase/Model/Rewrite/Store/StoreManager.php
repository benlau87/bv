<?php

namespace Rokanthemes\RokanBase\Model\Rewrite\Store;

class StoreManager extends \Magento\Store\Model\StoreManager
{
    public function getStore($storeId = null)
    {
        if (!isset($storeId) || '' === $storeId || $storeId === true) {
            if (null === $this->currentStoreId || '' === $this->currentStoreId) {
                \Magento\Framework\Profiler::start('store.resolve');
                $this->currentStoreId = $this->storeResolver->getCurrentStoreId();
                \Magento\Framework\Profiler::stop('store.resolve');
            }
            $storeId = $this->currentStoreId;
        }
        if ($storeId instanceof \Magento\Store\Api\Data\StoreInterface) {
            return $storeId;
        }

        $store = is_numeric($storeId)
            ? $this->storeRepository->getById($storeId)
            : $this->storeRepository->get($storeId);

        return $store;
    }
}
