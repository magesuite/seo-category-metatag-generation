<?php

namespace MageSuite\SeoCategoryMetatagGeneration\Controller\Adminhtml\Rule;

class Edit extends \Magento\Backend\App\Action
{
    /**
     * @var \MageSuite\SeoCategoryMetatagGeneration\Model\RuleFactory
     */
    protected $ruleFactory;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \MageSuite\SeoCategoryMetatagGeneration\Model\RuleFactory $ruleFactory
    )
    {
        parent::__construct($context);
        $this->ruleFactory = $ruleFactory;
    }

    public function execute()
    {
        $ruleId = $this->_request->getParam('rule_id');
        $rule = $this->ruleFactory->create();
        $rule->load($ruleId);

        if(!$rule->getId()) {
            throw new \Magento\Framework\Exception\NotFoundException(__('Rule does not exist'));
        }

        $resultPage = $this->resultFactory->create(\Magento\Framework\Controller\ResultFactory::TYPE_PAGE);
        $resultPage->getConfig()->getTitle()->set(__('Edit rule: '.$rule->getName()));

        return $resultPage;
    }
}