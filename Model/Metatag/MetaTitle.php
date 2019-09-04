<?php

namespace MageSuite\SeoCategoryMetatagGeneration\Model\Metatag;

class MetaTitle implements MetatagInterface
{
    const METATAG_KEY = 'meta_title';

    /**
     * @var \MageSuite\SeoCategoryMetatagGeneration\Helper\Configuration
     */
    protected $configuration;

    /**
     * @var \MageSuite\DynamicDirectives\Service\DirectiveApplier
     */
    protected $directiveApplier;

    public function __construct(
        \MageSuite\SeoCategoryMetatagGeneration\Helper\Configuration $configuration,
        \MageSuite\DynamicDirectives\Service\DirectiveApplier $directiveApplier
    ) {
        $this->configuration = $configuration;
        $this->directiveApplier = $directiveApplier;
    }

    public function isApplicable($key)
    {
        return $key == self::METATAG_KEY;
    }

    public function getText()
    {
        $metaTitle = $this->configuration->getMetaTitle();

        if (empty($metaTitle)) {
            return null;
        }

        return $this->directiveApplier->apply($metaTitle);
    }
}
