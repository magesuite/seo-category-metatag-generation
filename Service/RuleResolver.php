<?php

namespace MageSuite\SeoCategoryMetatagGeneration\Service;

class RuleResolver
{
    const APPLIED_METATAG_GENERATION_RULE_REGISTRY_KEY = 'applied_metatag_generation_rule';
    const METATAG_GENERATION_RULE_WAS_PROCESSED_REGISTRY_KEY = 'metatag_generation_rule_was_processed';
    const ALL_STORE_VIEWS_ID = 0;

    protected static $appliedRule = null;
    protected static $rulesWereProcessed = false;

    /**
     * @var \MageSuite\SeoCategoryMetatagGeneration\Model\ResourceModel\Rule\CollectionFactory
     */
    protected $ruleCollectionFactory;

    /**
     * @var \Magento\Framework\Registry
     */
    protected $registry;

    /**
     * @var \MageSuite\SeoCategoryMetatagGeneration\Model\FilterSelectionFactory
     */
    protected $filterSelectionFactory;

    /**
     * @var \Magento\Framework\App\Request\Http
     */
    protected $request;

    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product\Attribute\CollectionFactory
     */
    protected $attributeCollectionFactory;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    public function __construct(
        \MageSuite\SeoCategoryMetatagGeneration\Model\ResourceModel\Rule\CollectionFactory $ruleCollectionFactory,
        \Magento\Framework\Registry $registry,
        \MageSuite\SeoCategoryMetatagGeneration\Model\FilterSelectionFactory $filterSelectionFactory,
        \Magento\Catalog\Model\ResourceModel\Product\Attribute\CollectionFactory $attributeCollectionFactory,
        \Magento\Framework\App\Request\Http $request,
        \Magento\Store\Model\StoreManagerInterface $storeManager
    ) {
        $this->ruleCollectionFactory = $ruleCollectionFactory;
        $this->registry = $registry;
        $this->filterSelectionFactory = $filterSelectionFactory;
        $this->request = $request;
        $this->attributeCollectionFactory = $attributeCollectionFactory;
        $this->storeManager = $storeManager;
    }

    public function getApplicableRule()
    {
        if ($this->registry->registry(self::APPLIED_METATAG_GENERATION_RULE_REGISTRY_KEY)) {
            return $this->registry->registry(self::APPLIED_METATAG_GENERATION_RULE_REGISTRY_KEY);
        }

        if ($this->registry->registry(self::METATAG_GENERATION_RULE_WAS_PROCESSED_REGISTRY_KEY)) {
            return null;
        }

        $rule = $this->getRule();

        $this->registry->register(self::APPLIED_METATAG_GENERATION_RULE_REGISTRY_KEY, $rule);
        $this->registry->register(self::METATAG_GENERATION_RULE_WAS_PROCESSED_REGISTRY_KEY, true);

        return $rule;
    }

    public function getRule()
    {
        $ruleCollection = $this->ruleCollectionFactory->create();

        $category = $this->getCategory();

        if ($category == null) {
            return null;
        }

        $stores = [self::ALL_STORE_VIEWS_ID, $this->storeManager->getStore()->getId()];

        $ruleCollection->addFilter('category_id', $category->getId());
        $ruleCollection->setOrder('sort_order', 'ASC');
        $ruleCollection->addFieldToFilter('store_id', $stores);
        $rules = $ruleCollection->getItems();

        if (empty($rules)) {
            return null;
        }

        $filterSelection = $this->getFilterSelection();

        foreach ($rules as $rule) {
            $isValid = $rule->validate($filterSelection);

            if ($isValid) {
                return $rule;
            }
        }

        return null;
    }

    protected function getCategory()
    {
        $category = $this->registry->registry('current_category');

        return $category && $category->getId() ? $category : null;
    }

    protected function getFilterSelection()
    {
        $dataObject = $this->filterSelectionFactory->create();
        $params = $this->request->getParams();

        foreach ($params as $attributeCode => $values) {
            $dataObject->setData('is_selected_'.$attributeCode, true);
        }

        $attributeCollection = $this->attributeCollectionFactory->create();
        $attributeCollection
            ->addFieldToFilter(\Magento\Catalog\Api\Data\EavAttributeInterface::IS_FILTERABLE, true);

        $attributes = [];

        foreach ($attributeCollection->getItems() as $attribute) {
            $attributeCode = $attribute->getAttributeCode();

            $options = $attribute->getOptions();

            foreach ($options as $option) {
                $attributes[$attribute->getAttributeCode()]['options'][$option->getLabel()] = $option->getValue();
            }

            if (isset($params[$attributeCode])) {
                if (is_array($params[$attributeCode])) {
                    $values = [];

                    foreach ($params[$attributeCode] as $label) {
                        $values[] = $attributes[$attributeCode]['options'][$label] ?? null;
                    }

                    $dataObject->setData($attributeCode, $values);
                } else {
                    $label = $params[$attributeCode];
                    $value = $attributes[$attributeCode]['options'][$label] ?? null;
                    $dataObject->setData($attributeCode, $value);
                }
            }
        }

        return $dataObject;
    }
}
