<?php

namespace MageSuite\SeoCategoryMetatagGeneration\Ui\Component\Listing\Column;

class Actions extends \Magento\Ui\Component\Listing\Columns\Column
{
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as &$item) {
                $ruleId = $item['rule_id'];

                $editRule = $this->context->getUrl('category_metatag_generation/rule/edit', ['rule_id' => $ruleId]);
                $deleteRule = $this->context->getUrl('category_metatag_generation/rule/delete', ['rule_id' => $ruleId]);

                $item[$this->getData('name')] = [
                    'edit' => [
                        'href' => $editRule,
                        'label' => __('Edit')
                    ],
                    'remove' => [
                        'href' => $deleteRule ,
                        'label' => __('Delete'),
                        'confirm' => [
                            'title' => 'Delete rule',
                            'message' => 'Are you sure you want to delete this rule?'
                        ]
                    ],
                ];
            }
        }

        return $dataSource;
    }
}
