<?php

namespace MageSuite\SeoCategoryMetatagGeneration\Model\Directive\Directives\Filter;

class Values extends \MageSuite\DynamicDirectives\Model\Directive
{
    /**
     * @var \Magento\Framework\Registry
     */
    protected $registry;

    /**
     * @var \Magento\Framework\App\RequestInterface
     */
    protected $request;

    /**
     * @var \MageSuite\SeoLinkMasking\Service\FilterableAttributesProvider
     */
    protected $filterableAttributesProvider;

    public function __construct(
        \Magento\Framework\Registry $registry,
        \Magento\Framework\App\RequestInterface $request,
        \MageSuite\SeoLinkMasking\Service\FilterableAttributesProvider $filterableAttributesProvider
    ) {
        $this->registry = $registry;
        $this->request = $request;
        $this->filterableAttributesProvider = $filterableAttributesProvider;
    }

    public function getValue()
    {
        $category = $this->getCategory();

        if (empty($category)) {
            return null;
        }

        $params = $this->request->getQueryValue();

        if (empty($params)) {
            return null;
        }

        $filterableAttribute = $this->filterableAttributesProvider->getList($category);

        $result = [];

        foreach ($params as $key => $value) {
            if (!isset($filterableAttribute[$key])) {
                continue;
            }

            $result[] = $value;
        }

        return implode(' ', $result);
    }

    protected function getCategory()
    {
        $category = $this->registry->registry('current_category');

        return ($category && $category->getId()) ? $category : null;
    }
}
