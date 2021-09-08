<?php

include 'categories_rollback.php';

$objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();

$registry = $objectManager->get(\Magento\Framework\Registry::class);

$registry->unregister('isSecureArea');
$registry->register('isSecureArea', true);

$rulesCollection = $objectManager->get(\MageSuite\SeoCategoryMetatagGeneration\Model\ResourceModel\Rule\Collection::class);

foreach ($rulesCollection->getItems() as $rule) {
    if (!$rule->getId()) {
        continue;
    }

    $rule->delete();
}
