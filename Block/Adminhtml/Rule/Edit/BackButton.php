<?php

namespace MageSuite\SeoCategoryMetatagGeneration\Block\Adminhtml\Rule\Edit;

class BackButton implements \Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface
{
    /**
     * @var \Magento\Framework\App\Request\Http
     */
    protected $request;

    /**
     * @var \MageSuite\SeoCategoryMetatagGeneration\Model\RuleFactory
     */
    protected $ruleFactory;

    /**
     * @var \Magento\Framework\UrlInterface
     */
    protected $urlBuilder;

    public function __construct(
        \Magento\Framework\App\Request\Http $request,
        \Magento\Framework\UrlInterface $urlBuilder,
        \MageSuite\SeoCategoryMetatagGeneration\Model\RuleFactory $ruleFactory
    )
    {
        $this->request = $request;
        $this->ruleFactory = $ruleFactory;
        $this->urlBuilder = $urlBuilder;
    }

    public function getButtonData()
    {
        return [
            'label' => __('Back to category'),
            'on_click' => sprintf("location.href = '%s';", $this->getBackUrl()),
            'class' => 'back',
            'sort_order' => 10
        ];
    }

    public function getBackUrl() {
        $params = [];
        if($this->request->getParam('category_id')) {
            $params['id'] = $this->request->getParam('category_id');
        }

        if($this->request->getParam('rule_id')) {
            $rule = $this->ruleFactory->create();
            $rule->load($this->request->getParam('rule_id'));

            $params['id'] = $rule->getCategoryId();

            $stores = $rule->getStores();

            if(count($stores) == 1) {
                $params['store'] = array_shift($stores);
            }
        }


        return $this->urlBuilder->getUrl('catalog/category/edit', $params);
    }
}
