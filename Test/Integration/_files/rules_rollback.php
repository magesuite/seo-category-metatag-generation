<?php

include 'categories_rollback.php';

$objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();

$registry = $objectManager->get(\Magento\Framework\Registry::class);

$registry->unregister('isSecureArea');
$registry->register('isSecureArea', true);

$rulesIds = [1];

foreach ($rulesIds as $ruleId) {
    $rule = $objectManager->create(\MageSuite\SeoCategoryMetatagGeneration\Model\Rule::class);
    $rule->load($ruleId);

    if ($rule->getId()) {
        $rule->delete();
    }
}
