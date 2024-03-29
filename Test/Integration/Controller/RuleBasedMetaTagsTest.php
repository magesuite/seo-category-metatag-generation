<?php

namespace MageSuite\SeoCategoryMetatagGeneration\Test\Integration\Controller;

class RuleBasedMetaTagsTest extends \Magento\TestFramework\TestCase\AbstractController
{
    /**
     * @var \Magento\TestFramework\ObjectManager
     */
    protected $objectManager;

    public function setUp(): void
    {
        parent::setUp();

        $this->objectManager = \Magento\TestFramework\ObjectManager::getInstance();
    }

    /**
     * @magentoDbIsolation enabled
     * @magentoAppIsolation enabled
     * @magentoDataFixture Magento/Framework/Search/_files/filterable_attribute.php
     * @magentoDataFixture loadRules
     * @magentoDataFixture clearCache
     * @magentoConfigFixture current_store seo/category_metatag_generation/is_enabled 1
     * @dataProvider rulesTestCases
     */
    public function testItUsesRuleSettingWhenCorrectFilterWerePassed($params, $expectedTitle, $expectedDescription)
    {
        // From ElasticSuite 2.10.6 update Mapping class gets initialized too quickly before filterable attributes
        // are put into database. This causes missing field mapping errors. We need to remove all shared instances
        // of all ElasticSuite related classes in order for mapping to get properly regenerated when test is executed
        $this->removeElasticSuiteClassesInstances();

        $this->getRequest()->setParams($params);
        $this->dispatch('catalog/category/view/id/778');

        $response = $this->getResponse()->getBody();
        $head = $this->getHeadContents($response);

        $assertContains = method_exists($this, 'assertStringContainsString') ? 'assertStringContainsString' : 'assertContains';

        $this->$assertContains(sprintf('<meta name="title" content="%s"', $expectedTitle), $head);
        $this->$assertContains(sprintf('<meta name="description" content="%s"', $expectedDescription), $head);
    }

    public static function rulesTestCases()
    {
        return [
            'only_rule_option_was_passed' => [
                [
                    'id' => 778,
                    'select_attribute' => ['Option 1']
                ],
                'Title exact parameters',
                'Description exact parameters'
            ],
            'rule_more_option_was_passed' => [
                [
                    'id' => 778,
                    'select_attribute' => ['Option 1','Option 2']
                ],
                'Title exact parameters',
                'Description exact parameters'
            ],
            'exist_rule_applies' => [
                [
                    'id' => 778,
                    'select_attribute' => ['Option 2']
                ],
                'Title filter selected',
                'Description filter selected'
            ],
            'rules_does_not_apply_attribute_value_should_be_returned' => [
                [
                    'id' => 778,
                    'price' => ['0-100']
                ],
                'Meta title text',
                'Meta description text'
            ]
        ];
    }

    public static function loadRules()
    {
        require __DIR__ . '/../_files/rules.php';
    }

    public static function clearCache()
    {
        require __DIR__ . '/../_files/clear_cache.php';
    }

    public static function loadRulesRollback()
    {
        require __DIR__ . '/../_files/rules_rollback.php';
    }

    /**
     * @param $response
     * @return string
     */
    protected function getHeadContents($html)
    {
        $headPattern = '/<head[^>]*>(.*?)<\/head>/si';
        preg_match($headPattern, $html, $results);

        return $results[1];
    }

    protected function removeElasticSuiteClassesInstances() {
        $reflectionProperty = new \ReflectionProperty(\Magento\TestFramework\ObjectManager::class, '_sharedInstances');
        $reflectionProperty->setAccessible(true);
        $sharedInstances = $reflectionProperty->getValue($this->objectManager);

        foreach($sharedInstances as $className => $class) {
            if(strpos($className, 'Smile') === false) {
                continue;
            }

            $this->objectManager->removeSharedInstance($className);
        }
    }
}
