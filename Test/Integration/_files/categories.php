<?php
$objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();

$category = $objectManager->create(\Magento\Catalog\Model\Category::class);
$category->isObjectNew(true);
$category
    ->setId(777)
    ->setCreatedAt('2014-06-23 09:50:07')
    ->setName('Category without meta tags')
    ->setParentId(2)
    ->setPath('1/2/777')
    ->setLevel(3)
    ->setAvailableSortBy('name')
    ->setDefaultSortBy('name')
    ->setIsActive(true)
    ->setPosition(1)
    ->setAvailableSortBy(['position'])
    ->save()
    ->reindex();

$category = $objectManager->create(\Magento\Catalog\Model\Category::class);
$category->isObjectNew(true);
$category
    ->setId(778)
    ->setName('Category with meta tags')
    ->setCreatedAt('2014-06-23 09:50:07')
    ->setParentId(2)
    ->setPath('1/2/778')
    ->setLevel(3)
    ->setAvailableSortBy('name')
    ->setDefaultSortBy('name')
    ->setIsActive(true)
    ->setPosition(1)
    ->setAvailableSortBy(['position'])
    ->setMetaTitle('Meta title text')
    ->setMetaDescription('Meta description text')
    ->save()
    ->reindex();
