<?php
// config cache has to be cleared before attributes are added
// otherwise cached mapping list for ElasticSuite is retrieved and throws fatal error
$objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();

$cacheList = $objectManager->get(\Magento\Framework\App\Cache\TypeListInterface::class);
$cacheList->cleanType('config');
