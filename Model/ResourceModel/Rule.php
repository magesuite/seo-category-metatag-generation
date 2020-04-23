<?php

namespace MageSuite\SeoCategoryMetatagGeneration\Model\ResourceModel;

class Rule extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    public function __construct(
        \Magento\Framework\Model\ResourceModel\Db\Context $context,
        \Magento\Framework\Serialize\Serializer\Json $serializer,
        $connectionName = null
    ) {
        parent::__construct($context, $connectionName);

        $this->serializer = $serializer;
    }
    protected function _construct()
    {
        $this->_init('category_metatag_generation_rule', 'rule_id');
    }

    public function getRuleStoreIds($ruleId) {
        $connection = $this->getConnection();

        $select = $connection->select()
            ->from(['cmgrs' => $this->getTable('category_metatag_generation_rule_store')], 'store_id')
            ->join(
                ['cmgr' => $this->getMainTable()],
                'cmgrs.rule_id = cmgr.rule_id',
                []
            )
            ->where('cmgr.rule_id = :rule_id');

        return $connection->fetchCol($select, ['rule_id' => (int)$ruleId]);
    }

    protected function _afterLoad(\Magento\Framework\Model\AbstractModel $object)
    {
        $storeIds = $this->getRuleStoreIds($object->getId());
        $object->setStoreId($storeIds);

        return parent::_afterLoad($object);
    }

    protected function _afterSave(\Magento\Framework\Model\AbstractModel $object)
    {
        if($object->getStores()) {
            $oldStores = $this->getRuleStoreIds($object->getId());
            $newStores = (array)$object->getStores();
            if (empty($newStores)) {
                $newStores = (array)$object->getStoreId();
            }

            $table = $this->getTable('category_metatag_generation_rule_store');

            $delete = array_diff($oldStores, $newStores);

            if ($delete) {
                $where = [
                    'rule_id = ?' => (int)$object->getId(),
                    'store_id IN (?)' => $delete,
                ];
                $this->getConnection()->delete($table, $where);
            }

            $insert = array_diff($newStores, $oldStores);
            if ($insert) {
                $data = [];
                foreach ($insert as $storeId) {
                    $data[] = [
                        'rule_id' => (int)$object->getId(),
                        'store_id' => (int)$storeId
                    ];
                }
                $this->getConnection()->insertMultiple($table, $data);
            }
        }

        return parent::_afterSave($object);
    }
}