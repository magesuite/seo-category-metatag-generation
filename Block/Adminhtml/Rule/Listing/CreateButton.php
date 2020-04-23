<?php

namespace MageSuite\SeoCategoryMetatagGeneration\Block\Adminhtml\Rule\Listing;

class CreateButton extends \Magento\Backend\Block\Widget\Button
{
    public function getClass() {
        return 'action-secondary';
    }

    public function getLabel() {
        return __('Create new rule');
    }

    public function getTitle() {
        return __('Create new rule');
    }

    public function getOnClick()
    {
        $categoryId = $this->_request->getParam('id');
        $storeId = $this->_request->getParam('store', 0);
        $url = $this->_urlBuilder->getUrl('category_metatag_generation/rule/create', ['category_id' => $categoryId, 'store_id' => $storeId]);

        return sprintf("window.open('%s');", $url);
    }
}