<?php

namespace MageSuite\SeoCategoryMetatagGeneration\Model;

/**
 * @method \MageSuite\SeoCategoryMetatagGeneration\Model\ResourceModel\Rule getResource()
 * @method \MageSuite\SeoCategoryMetatagGeneration\Model\ResourceModel\Rule\Collection getCollection()
 */
class Rule extends \Magento\Rule\Model\AbstractModel
{
    const CACHE_TAG = 'magesuite_seocategorymetataggeneration_rule';

    protected $_cacheTag = 'magesuite_seocategorymetataggeneration_rule';

    protected $_eventPrefix = 'magesuite_seocategorymetataggeneration_rule';

    /**
     * @var Rule\Condition\CombineFactory
     */
    protected $conditionsCombineFactory;

    /**
     * @var \Magento\Rule\Model\Action\CollectionFactory
     */
    protected $actionsFactory;

    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Framework\Stdlib\DateTime\TimezoneInterface $localeDate,
        \MageSuite\SeoCategoryMetatagGeneration\Model\Rule\Condition\CombineFactory $conditionsCombineFactory,
        \Magento\Rule\Model\Action\CollectionFactory $actionsFactory,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        $this->conditionsCombineFactory = $conditionsCombineFactory;
        $this->actionsFactory = $actionsFactory;

        parent::__construct($context, $registry, $formFactory, $localeDate, $resource, $resourceCollection, $data);
    }

    protected function _construct()
    {
        $this->_init(\MageSuite\SeoCategoryMetatagGeneration\Model\ResourceModel\Rule::class);
    }

    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    public function getConditionsInstance()
    {
        return $this->conditionsCombineFactory->create();
    }

    public function getActionsInstance()
    {
        return $this->actionsFactory->create();
    }

    public function getStores()
    {
        return $this->hasData('stores') ? $this->getData('stores') : (array)$this->getData('store_id');
    }
}
