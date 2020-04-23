<?php

namespace MageSuite\SeoCategoryMetatagGeneration\Controller\Adminhtml\Rule;

class NewConditionHtml extends \Magento\Backend\App\Action
{
    /**
     * @var \MageSuite\SeoCategoryMetatagGeneration\Model\RuleFactory
     */
    protected $ruleFactory;

    public function __construct(
        \Magento\Backend\App\Action $context,
        \MageSuite\SeoCategoryMetatagGeneration\Model\RuleFactory $ruleFactory
    )
    {
        \Magento\Backend\App\Action::__construct($context);

        $this->ruleFactory = $ruleFactory;
    }

    public function execute()
    {
        $id = $this->getRequest()->getParam('id');
        $typeArr = explode('|', str_replace('-', '/', $this->getRequest()->getParam('type')));
        $type = $typeArr[0];

        $model = $this->_objectManager->create($type)
            ->setId($id)
            ->setType($type)
            ->setRule($this->ruleFactory->create())
            ->setPrefix('conditions');

        if (!empty($typeArr[1])) {
            $model->setAttribute($typeArr[1]);
        }

        if ($model instanceof \Magento\Rule\Model\Condition\AbstractCondition) {
            $model->setJsFormObject($this->getRequest()->getParam('form'));
            $html = $model->asHtmlRecursive();
        } else {
            $html = '';
        }

        $this->getResponse()->setBody($html);
    }
}