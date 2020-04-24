<?php

namespace MageSuite\SeoCategoryMetatagGeneration\Ui\Component\Listing\Rule;

class DataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    /**
     * @var \Magento\Framework\App\RequestInterface
     */
    protected $request;

    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        \MageSuite\SeoCategoryMetatagGeneration\Model\ResourceModel\Rule\CollectionFactory $ruleCollectionFactory,
        \Magento\Framework\App\RequestInterface $request,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $ruleCollectionFactory->create();
        $this->request = $request;

        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    public function getData()
    {
        if ($this->request->getParam('category_id')) {
            $this->collection->addFieldToFilter('category_id', $this->request->getParam('category_id'));
        }

        if ($this->request->getParam('store_id')) {
            $this->collection->addFieldToFilter('store_id', [$this->request->getParam('store_id')]);
        } else {
            $this->collection->addFieldToFilter('store_id', [0]);
        }

        return parent::getData();
    }
}
