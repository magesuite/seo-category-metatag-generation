<?php

declare(strict_types=1);

namespace MageSuite\SeoCategoryMetatagGeneration\Test\Integration\Controller;

class RuleBasedMetaTagsTest extends \Magento\TestFramework\TestCase\AbstractController
{
    protected ?\Magento\TestFramework\ObjectManager $objectManager;

    public function setUp(): void
    {
        parent::setUp();

        $this->objectManager = \Magento\TestFramework\ObjectManager::getInstance();
    }

    /**
     * @magentoDbIsolation enabled
     * @magentoAppIsolation enabled
     * @magentoDataFixture Magento/Framework/Search/_files/filterable_attribute.php
     * @magentoDataFixture MageSuite_SeoCategoryMetatagGeneration::Test/Integration/_files/rules.php
     * @magentoDataFixture MageSuite_SeoCategoryMetatagGeneration::Test/Integration/_files/clear_cache.php
     * @magentoConfigFixture current_store seo/category_metatag_generation/is_enabled 1
     * @dataProvider rulesTestCases
     */
    public function testItUsesRuleSettingWhenCorrectFilterWerePassed(array $params, string $expectedTitle, string $expectedDescription): void
    {
        // From ElasticSuite 2.10.6 update Mapping class gets initialized too quickly before filterable attributes
        // are put into a database. This causes missing field mapping errors. We need to remove all shared instances
        // of all ElasticSuite related classes in order for mapping to get properly regenerated when the test is executed
        $this->removeElasticSuiteClassesInstances();

        $this->getRequest()->setParams($params);
        $this->dispatch('catalog/category/view/id/778');

        $response = $this->getResponse()->getBody();
        $head = $this->getHeadContents($response);

        $assertContains = method_exists($this, 'assertStringContainsString') ? 'assertStringContainsString' : 'assertContains';

        $this->$assertContains(sprintf('<meta name="title" content="%s"', $expectedTitle), $head);
        $this->$assertContains(sprintf('<meta name="description" content="%s"', $expectedDescription), $head);
    }

    public static function rulesTestCases(): array
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
                    'select_attribute' => ['Option 1', 'Option 2']
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

    protected function getHeadContents(string $html): string
    {
        $headPattern = '/<head[^>]*>(.*?)<\/head>/si';
        preg_match($headPattern, $html, $results);

        return $results[1];
    }

    protected function removeElasticSuiteClassesInstances(): void
    {
        $reflectionProperty = new \ReflectionProperty(\Magento\TestFramework\ObjectManager::class, '_sharedInstances');
        $sharedInstances = $reflectionProperty->getValue($this->objectManager);

        foreach ($sharedInstances as $className => $class) {
            if (!str_contains($className, 'Smile')) {
                continue;
            }

            $this->objectManager->removeSharedInstance($className);
        }
    }
}
