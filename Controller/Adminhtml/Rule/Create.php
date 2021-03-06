<?php

namespace MageSuite\SeoCategoryMetatagGeneration\Controller\Adminhtml\Rule;

class Create extends \Magento\Backend\App\Action
{
    public function execute()
    {
        $resultPage = $this->resultFactory->create(\Magento\Framework\Controller\ResultFactory::TYPE_PAGE);
        $resultPage->getConfig()->getTitle()->set(__('Create rule'));

        return $resultPage;
    }
}
