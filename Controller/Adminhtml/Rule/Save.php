<?php

namespace MageSuite\SeoCategoryMetatagGeneration\Controller\Adminhtml\Rule;

class Save extends \Magento\Backend\App\Action
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
        \Magento\Backend\App\Action::__construct($context);

        $this->ruleFactory = $ruleFactory;
    }

    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();

        $ruleData = $this->getRequest()->getPostValue();

        try {
            $rule = $this->ruleFactory->create();

            $rule->loadPost($ruleData['rule']);
            $rule->save();

            $this->messageManager->addSuccessMessage('Rule was saved');

            return $resultRedirect->setPath('*/*/edit', ['rule_id' => $rule->getId()]);
        }
        catch(\Exception $e) {
            $this->messageManager->addErrorMessage(__('There was an error while saving rule: %1', $e->getMessage()));

            return $resultRedirect->setPath('*/*/create');
        }
    }
}