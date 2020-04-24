<?php

namespace MageSuite\SeoCategoryMetatagGeneration\Ui\Component\Control;

class SaveButton implements \Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface
{
    public function getButtonData()
    {
        return [
            'label' => __('Save'),
            'class' => 'save primary',
            'class_name' => \Magento\Ui\Component\Control\Container::DEFAULT_CONTROL,
            'options' => [],
            'sort_order' => 90,
        ];
    }
}
