<?php

namespace MageSuite\SeoCategoryMetatagGeneration\Helper;

class Configuration extends \Magento\Framework\App\Helper\AbstractHelper
{
    const XML_PATH_SEO_CATEGORY_METATAG_GENERATION_CONFIGURATION = 'seo/category_metatag_generation';

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    protected $config = null;

    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfigInterface
    ) {
        parent::__construct($context);

        $this->scopeConfig = $scopeConfigInterface;
    }

    public function isEnabled()
    {
        return (bool)$this->getConfig()->getIsEnabled();
    }

    public function getMetaTitle()
    {
        if (!$this->isEnabled()) {
            return null;
        }

        return $this->getConfig()->getMetaTitle();
    }

    public function getMetaDescription()
    {
        if (!$this->isEnabled()) {
            return null;
        }

        return $this->getConfig()->getMetaDescription();
    }

    protected function getConfig()
    {
        if ($this->config === null) {
            $this->config = new \Magento\Framework\DataObject(
                $this->scopeConfig->getValue(self::XML_PATH_SEO_CATEGORY_METATAG_GENERATION_CONFIGURATION, \Magento\Store\Model\ScopeInterface::SCOPE_STORE)
            );
        }

        return $this->config;
    }
}
