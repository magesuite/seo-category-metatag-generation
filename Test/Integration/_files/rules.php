<?php
$objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();

include 'categories.php';

if (!function_exists('getOptionId')) {
    function getOptionId($attributeCode, $label)
    {
        $objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();

        /** @var \Magento\Eav\Model\Entity\Attribute $attribute */
        $attribute = $objectManager->create(\Magento\Eav\Model\Entity\Attribute::class);
        $attribute->loadByCode('catalog_product', $attributeCode);

        $options = $attribute->getOptions();

        foreach ($options as $option) {
            if ($option->getLabel() == $label) {
                return $option->getValue();
            }
        }

        return null;
    }
}

$rule = $objectManager->create(\MageSuite\SeoCategoryMetatagGeneration\Model\Rule::class);
$rule->loadPost(
    [
        'name' => 'Rule with different category id',
        'meta_title' => 'Title with different category id',
        'meta_description' => 'Description with different category id',
        'category_id' => 777,
        'sort_order' => 10,
        'store_id' => [0 => '0'],
        'conditions' =>
            [
                '1' =>
                    [
                        'type' => 'Magento\\Rule\\Model\\Condition\\Combine',
                        'aggregator' => 'all',
                        'value' => '1',
                        'new_child' => '',
                    ],
                '1--1' =>
                    [
                        'type' => 'MageSuite\\SeoCategoryMetatagGeneration\\Model\\Rule\\Condition\\FilterSelected',
                        'attribute' => 'select_attribute',
                        'operator' => '==',
                        'value' => 1
                    ],
            ],
    ]
);

$rule->save();

/** @var \MageSuite\SeoCategoryMetatagGeneration\Model\Rule $rule */
$rule = $objectManager->create(\MageSuite\SeoCategoryMetatagGeneration\Model\Rule::class);
$rule->loadPost(
    [
        'name' => 'Rule with exact parameters',
        'meta_title' => 'Title exact parameters',
        'meta_description' => 'Description exact parameters',
        'category_id' => 778,
        'sort_order' => 20,
        'store_id' => [0 => '0'],
        'conditions' =>
            [
                '1' =>
                    [
                        'type' => 'Magento\\Rule\\Model\\Condition\\Combine',
                        'aggregator' => 'all',
                        'value' => '1',
                        'new_child' => '',
                    ],
                '1--1' =>
                    [
                        'type' => 'MageSuite\\SeoCategoryMetatagGeneration\\Model\\Rule\\Condition\\FilterValue',
                        'attribute' => 'select_attribute',
                        'operator' => '{}',
                        'value' =>
                            [
                                0 => getOptionId('select_attribute', 'Option 1'),
                            ],
                    ],
            ],
    ]
);


$rule->save();

$rule = $objectManager->create(\MageSuite\SeoCategoryMetatagGeneration\Model\Rule::class);
$rule->loadPost(
    [
        'name' => 'Rule with filter selected',
        'meta_title' => 'Title filter selected',
        'meta_description' => 'Description filter selected',
        'category_id' => 778,
        'sort_order' => 30,
        'store_id' => [0 => '0'],
        'conditions' =>
            [
                '1' =>
                    [
                        'type' => 'Magento\\Rule\\Model\\Condition\\Combine',
                        'aggregator' => 'all',
                        'value' => '1',
                        'new_child' => '',
                    ],
                '1--1' =>
                    [
                        'type' => 'MageSuite\\SeoCategoryMetatagGeneration\\Model\\Rule\\Condition\\FilterSelected',
                        'attribute' => 'select_attribute',
                        'operator' => '==',
                        'value' => 1
                    ],
            ],
    ]
);

$rule->save();
