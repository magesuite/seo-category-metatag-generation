<?php

namespace MageSuite\SeoCategoryMetatagGeneration\Model\Directive\Directives\Category;

class Name extends \MageSuite\DynamicDirectives\Model\Directive
{
    /**
     * @var \Magento\Framework\Registry
     */
    protected $registry;

    public function __construct(\Magento\Framework\Registry $registry)
    {
        $this->registry = $registry;
    }

    public function getValue()
    {
        $category = $this->getCategory();

        if (empty($category)) {
            return null;
        }

        return $category->getName();
    }

    protected function getCategory()
    {
        $category = $this->registry->registry('current_category');

        return ($category && $category->getId()) ? $category : null;
    }
}
