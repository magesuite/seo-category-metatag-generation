<?php

namespace MageSuite\SeoCategoryMetatagGeneration\Controller\Adminhtml\Rule;

class Delete extends \Magento\Backend\App\AbstractAction
{
    /**
     * @var \MageSuite\SeoCategoryMetatagGeneration\Model\RuleFactory
     */
    protected $ruleFactory;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \MageSuite\SeoCategoryMetatagGeneration\Model\RuleFactory $ruleFactory
    ) {
        parent::__construct($context);

        $this->ruleFactory = $ruleFactory;
    }

    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();

        $ruleId = $this->_request->getParam('rule_id');

        /** @var \MageSuite\SeoCategoryMetatagGeneration\Model\Rule $rule */
        $rule = $this->ruleFactory->create();
        $rule->load($ruleId);

        if (!$rule->getId()) {
            $this->messageManager->addErrorMessage(__('We can\'t find a rule to delete.'));

            return $resultRedirect->setRefererOrBaseUrl();
        }

        $rule->delete();

        $this->messageManager->addSuccessMessage(__('Rule was deleted'));

        return $resultRedirect->setRefererOrBaseUrl();
    }
}
