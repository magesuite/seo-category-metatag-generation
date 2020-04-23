<?php

namespace MageSuite\SeoCategoryMetatagGeneration\Model\ResourceModel\Rule;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{

    /**
     * @var string
     */
    protected $_idFieldName = 'rule_id';

    protected function _construct()
    {
        $this->_init(
            \MageSuite\SeoCategoryMetatagGeneration\Model\Rule::class,
            \MageSuite\SeoCategoryMetatagGeneration\Model\ResourceModel\Rule::class
        );

        $this->_map['fields']['store'] = 'store_table.store_id';
    }

    protected function _afterLoad()
    {
        $ruleIds = $this->getColumnValues($this->_idFieldName);

        if (empty($ruleIds)) {
            return parent::_afterLoad();
        }

        $connection = $this->getConnection();
        $select = $connection->select()
            ->from(['rule_to_store' => $this->getTable('category_metatag_generation_rule_store')])
            ->where('rule_to_store.' . $this->_idFieldName . ' IN (?)', $ruleIds);

        $result = $connection->fetchAll($select);

        if (!$result) {
            return parent::_afterLoad();
        }

        $ruleToStores = [];

        foreach ($result as $storeData) {
            $ruleToStores[$storeData[$this->_idFieldName]][] = $storeData['store_id'];
        }

        foreach ($this as $rule) {
            $ruleId = $rule->getData($this->_idFieldName);

            if (!isset($ruleToStores[$ruleId])) {
                continue;
            }

            $rule->setData('store_id', $ruleToStores[$ruleId]);
        }

        return parent::_afterLoad();
    }

    public function addFieldToFilter($field, $condition = null)
    {
        if ($field === 'store_id') {
            return $this->addStoreFilter($condition);
        }

        return parent::addFieldToFilter($field, $condition);
    }

    protected function addStoreFilter($store) {
        if ($store instanceof \Magento\Store\Model\Store) {
            $store = [$store->getId()];
        }

        if (!is_array($store)) {
            $store = [$store];
        }

        $this->addFilter('store', ['in' => $store], 'public');
    }

    protected function _renderFiltersBefore()
    {
        if ($this->getFilter('store')) {
            $this->getSelect()->join(
                ['store_table' => $this->getTable('category_metatag_generation_rule_store')],
                'main_table.rule_id = store_table.rule_id',
                []
            )->group(
                'main_table.rule_id'
            );
        }

        parent::_renderFiltersBefore();
    }

}