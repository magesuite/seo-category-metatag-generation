<?php

namespace MageSuite\SeoCategoryMetatagGeneration\Test\Integration\Model;

/**
 * @magentoDbIsolation enabled
 * @magentoAppIsolation enabled
 */
class CategoryTest extends \PHPUnit\Framework\TestCase
{
    const CATEGORY_WITHOUT_META_TAGS = 777;
    const CATEGORY_WITH_META_TAGS = 778;

    /**
     * @var \Magento\Framework\Registry
     */
    protected $registry;

    /**
     * @var \Magento\Catalog\Api\CategoryRepositoryInterface
     */
    protected $categoryRepository;

    public function setUp()
    {
        $objectManager = \Magento\TestFramework\ObjectManager::getInstance();
        $this->registry = $objectManager->get(\Magento\Framework\Registry::class);
        $this->categoryRepository = $objectManager->create(\Magento\Catalog\Api\CategoryRepositoryInterface::class);
    }

    /**
     * @magentoAppArea frontend
     * @magentoDbIsolation enabled
     * @magentoAppIsolation enabled
     * @magentoDataFixture loadCategories
     * @magentoConfigFixture current_store seo/category_metatag_generation/is_enabled 1
     * @magentoConfigFixture current_store seo/category_metatag_generation/meta_title Meta Title {{category_name}}
     * @magentoConfigFixture current_store seo/category_metatag_generation/meta_description Meta Description {{category_name}}
     * @dataProvider dataProvider
     * @param integer $categoryId
     * @param string $expectedMetaTitle
     * @param string $expectedMetaDescription
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function testItReturnsCorrectAttributeValue($categoryId, $expectedMetaTitle, $expectedMetaDescription)
    {
        $category = $this->categoryRepository->get($categoryId);

        if ($this->registry->registry('current_category')) {
            $this->registry->unregister('current_category');
        }
        $this->registry->register('current_category', $category);

        $this->assertEquals($expectedMetaTitle, $category->getMetaTitle());
        $this->assertEquals($expectedMetaDescription, $category->getMetaDescription());
    }

    /**
     * @return array
     */
    public function dataProvider()
    {
        return [
            [self::CATEGORY_WITHOUT_META_TAGS, 'Meta Title Category without meta tags', 'Meta Description Category without meta tags'],
            [self::CATEGORY_WITH_META_TAGS, 'Meta title text', 'Meta description text']
        ];
    }

    public static function loadCategories()
    {
        require __DIR__.'/../_files/categories.php';
    }

    public static function loadCategoriesRollback()
    {
        require __DIR__.'/../_files/categories_rollback.php';
    }
}
