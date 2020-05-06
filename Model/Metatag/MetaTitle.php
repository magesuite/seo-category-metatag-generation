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

    /**
     * @var \MageSuite\SeoCategoryMetatagGeneration\Service\RuleResolver
     */
    protected $ruleResolver;

    public function __construct(
        \MageSuite\SeoCategoryMetatagGeneration\Helper\Configuration $configuration,
        \MageSuite\DynamicDirectives\Service\DirectiveApplier $directiveApplier,
        \MageSuite\SeoCategoryMetatagGeneration\Service\RuleResolver $ruleResolver
    ) {
        $this->configuration = $configuration;
        $this->directiveApplier = $directiveApplier;
        $this->ruleResolver = $ruleResolver;
    }

    public function isApplicable($key)
    {
        return $key == self::METATAG_KEY;
    }

    public function getText($entityValue)
    {
        $metaTitle = $this->getMetaTitle($entityValue);

        return $this->directiveApplier->apply($metaTitle);
    }

    protected function getMetaTitle($entityValue)
    {
        $rule = $this->ruleResolver->getApplicableRule();

        if ($rule == null && !empty($entityValue)) {
            return $entityValue;
        }

        if (!empty($rule) && !empty($rule->getMetaTitle())) {
            return $rule->getMetaTitle();
        }

        $metaTitle = $this->configuration->getMetaTitle();

        if (empty($metaTitle)) {
            return null;
        }

        return $metaTitle;
    }
}
