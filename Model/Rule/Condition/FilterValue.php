<?php


namespace MageSuite\SeoCategoryMetatagGeneration\Model\Rule\Condition;

class FilterValue extends \Magento\Rule\Model\Condition\AbstractCondition
{
    const ADMIN_STORE_VIEW = 0;

    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product\Attribute\CollectionFactory
     */
    protected $attributeCollectionFactory;

    /**
     * @var \Magento\Eav\Model\Config
     */
    protected $eavConfig;

    /**
     * @param \Magento\Rule\Model\Condition\Context $context
     * @param \Magento\Catalog\Model\ResourceModel\Product\Attribute\CollectionFactory $attributeCollectionFactory
     * @param array $data
     */
    public function __construct(
        \Magento\Rule\Model\Condition\Context $context,
        \Magento\Catalog\Model\ResourceModel\Product\Attribute\CollectionFactory $attributeCollectionFactory,
        \Magento\Eav\Model\Config $eavConfig,
        array $data = []
    ) {
        $this->attributeCollectionFactory = $attributeCollectionFactory;
        $this->eavConfig = $eavConfig;

        parent::__construct($context, $data);
    }

    public function loadAttributeOptions()
    {
        /** @var \Magento\Catalog\Model\ResourceModel\Product\Attribute\Collection $attributeCollection */
        $attributeCollection = $this->attributeCollectionFactory->create();
        $attributeCollection
            ->addFieldToFilter(\Magento\Catalog\Api\Data\EavAttributeInterface::IS_FILTERABLE, true)
            ->addFieldToFilter(
                \Magento\Eav\Api\Data\AttributeInterface::FRONTEND_INPUT,
                [
                    'select' => 'select',
                    'multiselect' => 'multiselect',
                    'swatch' => 'swatch'
                ]
            );

        $attributes = [];
        foreach ($attributeCollection->getItems() as $attribute) {
            $attributes[$attribute->getAttributeCode()] = $attribute->getFrontendLabel();
        }

        $this->setAttributeOption($attributes);

        return $this;
    }

    /**
     * Get attribute element
     *
     * @return $this
     */
    public function getAttributeElement()
    {
        $element = parent::getAttributeElement();
        $element->setShowAsText(true);
        return $element;
    }

    /**
     * Get input type
     *
     * @return string
     */
    public function getInputType()
    {
        return 'multiselect';
    }

    /**
     * Get value element type
     *
     * @return string
     */
    public function getValueElementType()
    {
        return 'multiselect';
    }

    /**
     * Get value select options
     *
     * @return array|mixed
     */
    public function getValueSelectOptions()
    {
        if (!$this->hasData('value_select_options')) {
            $attributeCode = $this->getAttribute();

            $eavAttribute = $this->eavConfig
                ->getAttribute(\Magento\Catalog\Api\Data\ProductAttributeInterface::ENTITY_TYPE_CODE, $attributeCode)
                ->setStoreId(self::ADMIN_STORE_VIEW);

            $options = $eavAttribute
                ->getSource()
                ->getAllOptions();

            $this->setData('value_select_options', $options);
        }
        return $this->getData('value_select_options');
    }
}
