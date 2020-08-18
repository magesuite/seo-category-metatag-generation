<?php

namespace MageSuite\SeoCategoryMetatagGeneration\Model\Metatag;

class MetaDescription implements MetatagInterface
{
    const METATAG_KEY = 'meta_description';
    const OG_KEY = 'og_description';

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
        return in_array($key, [self::METATAG_KEY, self::OG_KEY], true);
    }

    public function getText($entityValue)
    {
        $metaDescription = $this->getMetaDescription($entityValue);

        return $this->directiveApplier->apply($metaDescription);
    }

    protected function getMetaDescription($entityValue)
    {
        $rule = $this->ruleResolver->getApplicableRule();

        if ($rule == null && !empty($entityValue)) {
            return $entityValue;
        }

        if (!empty($rule) && !empty($rule->getMetaDescription())) {
            return $rule->getMetaDescription();
        }

        $metaDescription = $this->configuration->getMetaDescription();

        if (empty($metaDescription)) {
            return null;
        }

        return $metaDescription;
    }
}
