<?php

namespace MageSuite\SeoCategoryMetatagGeneration\Model\Rule\Condition;

class Combine extends \Magento\Rule\Model\Condition\Combine
{
    /**
     * @var FilterValue
     */
    protected $conditionFilterValue;

    /**
     * @var FilterSelected
     */
    protected $conditionFilterSelected;

    /**
     * @param \Magento\Rule\Model\Condition\Context $context
     * @param \Magento\Framework\Event\ManagerInterface $eventManager
     * @param \Magento\SalesRule\Model\Rule\Condition\Address $conditionAddress
     * @param array $data
     */
    public function __construct(
        \Magento\Rule\Model\Condition\Context $context,
        \MageSuite\SeoCategoryMetatagGeneration\Model\Rule\Condition\FilterValue $conditionFilterValue,
        \MageSuite\SeoCategoryMetatagGeneration\Model\Rule\Condition\FilterSelected $conditionFilterSelected,
        array $data = []
    ) {
        $this->setType(\Magento\SalesRule\Model\Rule\Condition\Combine::class);

        $this->conditionFilterValue = $conditionFilterValue;
        $this->conditionFilterSelected = $conditionFilterSelected;

        parent::__construct($context, $data);
    }

    /**
     * Get new child select options
     *
     * @return array
     */
    public function getNewChildSelectOptions()
    {
        $filters = $this->conditionFilterValue->loadAttributeOptions()
            ->getAttributeOption();

        $valueAttributes = [];
        foreach ($filters as $code => $label) {
            $valueAttributes[] = [
                'value' => 'MageSuite\SeoCategoryMetatagGeneration\Model\Rule\Condition\FilterValue|' . $code,
                'label' => $label,
            ];
        }

        $filters = $this->conditionFilterSelected->loadAttributeOptions()
            ->getAttributeOption();

        $selectionAttributes = [];

        foreach ($filters as $code => $label) {
            $selectionAttributes[] = [
                'value' => 'MageSuite\SeoCategoryMetatagGeneration\Model\Rule\Condition\FilterSelected|' . $code,
                'label' => $label,
            ];
        }

        $conditions = parent::getNewChildSelectOptions();

        $conditions = array_merge_recursive(
            $conditions,
            [
                [
                    'label' => __('Conditions combination'),
                    'value' => \MageSuite\SeoCategoryMetatagGeneration\Model\Rule\Condition\Combine::class
                ],
                [
                    'label' => __('Filter is selected'),
                    'value' => $selectionAttributes
                ],
                [
                    'label' => __('Filter value'),
                    'value' => $valueAttributes
                ],
            ]
        );

        return $conditions;
    }
}
