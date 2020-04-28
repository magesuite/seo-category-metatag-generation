<?php

namespace MageSuite\SeoCategoryMetatagGeneration\Block\Rule\Fieldset;

class Conditions extends \Magento\Backend\Block\Widget\Form\Generic
{
    /**
     * @var string
     */
    protected $_nameInLayout = 'conditions_serialized';

    /**
     * @var \Magento\Rule\Block\Conditions
     */
    protected $conditions;

    /**
     * @var \MageSuite\SeoCategoryMetatagGeneration\Model\RuleFactory
     */
    protected $ruleFactory;

    /**
     * @var \Magento\Backend\Block\Widget\Form\Renderer\Fieldset
     */
    protected $rendererFieldset;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Rule\Block\Conditions $conditions,
        \MageSuite\SeoCategoryMetatagGeneration\Model\RuleFactory $ruleFactory,
        \Magento\Backend\Block\Widget\Form\Renderer\Fieldset $rendererFieldset,
        array $data = []
    )
    {
        $this->rendererFieldset = $rendererFieldset;
        $this->conditions = $conditions;
        $this->ruleFactory = $ruleFactory;

        parent::__construct($context, $registry, $formFactory, $data);
    }

    protected function _prepareForm()
    {
        $rule = $this->ruleFactory->create();

        if ($this->getRequest()->getParam('rule_id')) {
            $rule->load($this->getRequest()->getParam('rule_id'));
        }

        $form = $this->addTabToForm($rule);

        $this->setForm($form);

        return parent::_prepareForm();
    }

    protected function addTabToForm($rule)
    {
        $formName = 'category_metatag_generation_rule_form';

        $conditionsFieldSetId = $rule->getConditionsFieldSetId($formName);

        $newChildUrl = $this->getUrl(
            sprintf('sales_rule/promo_quote/newConditionHtml/form/%s', $conditionsFieldSetId),
            ['form_namespace' => $formName]
        );

        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create();
        $form->setHtmlIdPrefix('rule_');

        $renderer = $this->rendererFieldset
            ->setTemplate('Magento_CatalogRule::promo/fieldset.phtml')
            ->setNewChildUrl($newChildUrl)
            ->setFieldSetId($conditionsFieldSetId);

        $fieldset = $form
            ->addFieldset('conditions_serialized', [])
            ->setRenderer($renderer);

        $fieldset
            ->addField(
                'conditions',
                'text',
                [
                    'name' => 'conditions',
                    'label' => __('Conditions'),
                    'title' => __('Conditions'),
                    'required' => true,
                    'data-form-part' => $formName
                ]
            )
            ->setRule($rule)
            ->setRenderer($this->conditions);

        $form->setValues($rule->getData());
        $this->setConditionFormName($rule->getConditions(), $formName);

        return $form;
    }

    private function setConditionFormName(\Magento\Rule\Model\Condition\AbstractCondition $conditions, $formName)
    {
        $conditions->setFormName($formName);

        if ($conditions->getConditions() && is_array($conditions->getConditions())) {
            foreach ($conditions->getConditions() as $condition) {
                $this->setConditionFormName($condition, $formName);
            }
        }
    }

    public function getTabClass()
    {
        return null;
    }

    public function getTabUrl()
    {
        return null;
    }

    public function isAjaxLoaded()
    {
        return false;
    }

    public function getTabLabel()
    {
        return __('Conditions');
    }

    public function getTabTitle()
    {
        return __('Conditions');
    }

    public function canShowTab()
    {
        return true;
    }

    public function isHidden()
    {
        return false;
    }
}
