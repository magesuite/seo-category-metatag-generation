<?php

namespace MageSuite\SeoCategoryMetatagGeneration\Controller\Adminhtml\Rule;

class Validate extends \Magento\Backend\App\Action
{
    /**
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */
    protected $resultJsonFactory;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
    ) {
        \Magento\Backend\App\Action::__construct($context);

        $this->resultJsonFactory = $resultJsonFactory;
    }

    public function execute()
    {
        $response = new \Magento\Framework\DataObject();
        $response->setError(false);

        return $this->resultJsonFactory->create()->setData($response);
    }
}
