<?php

namespace MageSuite\SeoCategoryMetatagGeneration\Ui\Component\Form\Rule;

class DataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    protected $loadedData;

    /**
     * @var \Magento\Framework\App\Request\Http
     */
    protected $request;

    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        \MageSuite\SeoCategoryMetatagGeneration\Model\ResourceModel\Rule\CollectionFactory $ruleCollectionFactory,
        \Magento\Framework\App\Request\Http $request,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $ruleCollectionFactory->create();
        $this->request = $request;

        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    public function getData()
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }

        $rules = $this->collection->getItems();

        foreach ($rules as $rule) {
            $this->loadedData[$rule->getId()]['rule'] = $rule->getData();
        }

        if(empty($this->loadedData)) {
            if($this->request->getParam('category_id') and empty($this->loadedData)) {
                $this->loadedData[null]['rule']['category_id'] = $this->request->getParam('category_id');
            }

            if($this->request->getParam('store_id')) {
                $this->loadedData[null]['rule']['store_id'] = [$this->request->getParam('store_id')];
            }
        }

        return $this->loadedData;
    }
}
