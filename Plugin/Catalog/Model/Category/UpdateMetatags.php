<?php

namespace MageSuite\SeoCategoryMetatagGeneration\Plugin\Catalog\Model\Category;

class UpdateMetatags
{
    /**
     * @var \MageSuite\SeoCategoryMetatagGeneration\Helper\Configuration
     */
    protected $configuration;

    /**
     * @var \MageSuite\SeoCategoryMetatagGeneration\Model\MetatagGenerator
     */
    protected $metatagGenerator;

    protected $keys;

    public function __construct(
        \MageSuite\SeoCategoryMetatagGeneration\Helper\Configuration $configuration,
        \MageSuite\SeoCategoryMetatagGeneration\Model\MetatagGenerator $metatagGenerator,
        array $keys
    ) {
        $this->configuration = $configuration;
        $this->metatagGenerator = $metatagGenerator;
        $this->keys = $keys;
    }

    public function afterGetData(\Magento\Catalog\Model\Category $subject, $result, $key = '', $index = null)
    {
        if (!$this->configuration->isEnabled()) {
            return $result;
        }

        if (!in_array($key, $this->keys)) {
            return $result;
        }

        return $this->metatagGenerator->generate($key, $result);
    }
}
