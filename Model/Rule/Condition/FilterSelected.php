<?php


namespace MageSuite\SeoCategoryMetatagGeneration\Model\Rule\Condition;

class FilterSelected extends \Magento\Rule\Model\Condition\AbstractCondition
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
    )
    {
        $this->attributeCollectionFactory = $attributeCollectionFactory;
        $this->eavConfig = $eavConfig;

        parent::__construct($context, $data);
    }

    public function loadAttributeOptions()
    {
        /** @var \Magento\Catalog\Model\ResourceModel\Product\Attribute\Collection $attributeCollection */
        $attributeCollection = $this->attributeCollectionFactory->create();
        $attributeCollection
            ->addFieldToFilter(\Magento\Catalog\Api\Data\EavAttributeInterface::IS_FILTERABLE, true);

        $attributes = [];
        foreach($attributeCollection->getItems() as $attribute) {
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
        return 'boolean';
    }

    /**
     * Get value element type
     *
     * @return string
     */
    public function getValueElementType()
    {
        return 'select';
    }

    /**
     * Get value select options
     *
     * @return array|mixed
     */
    public function getValueSelectOptions()
    {
        return [
            [
                'label' => __('selected'),
                'value' => true
            ]
        ];
    }

    /**
     * Validate Address Rule Condition
     *
     * @param \Magento\Framework\Model\AbstractModel $model
     * @return bool
     */
    public function validate(\Magento\Framework\Model\AbstractModel $model)
    {
        $this->setData('attribute', 'is_selected_'.$this->getAttribute());
        return parent::validate($model);
    }
}
