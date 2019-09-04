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

    protected $tags = ['meta_title', 'meta_description'];

    public function __construct(
        \MageSuite\SeoCategoryMetatagGeneration\Helper\Configuration $configuration,
        \MageSuite\SeoCategoryMetatagGeneration\Model\MetatagGenerator $metatagGenerator
    ) {
        $this->configuration = $configuration;
        $this->metatagGenerator = $metatagGenerator;
    }

    public function aroundGetData(\Magento\Catalog\Model\Category $subject, \Closure $proceed, $key = '', $index = null)
    {
        $result = $proceed($key, $index);

        if (!empty($result)) {
            return $result;
        }

        if (!$this->configuration->isEnabled()) {
            return $result;
        }

        if (!in_array($key, $this->tags)) {
            return $result;
        }

        return $this->metatagGenerator->generate($key);
    }
}
