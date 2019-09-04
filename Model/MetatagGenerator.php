<?php

namespace MageSuite\SeoCategoryMetatagGeneration\Model;

class MetatagGenerator
{
    /**
     * @var \MageSuite\SeoCategoryMetatagGeneration\Model\MetatagPool
     */
    protected $metatagPool;

    public function __construct(\MageSuite\SeoCategoryMetatagGeneration\Model\MetatagPool $metatagPool)
    {
        $this->metatagPool = $metatagPool;
    }

    public function generate($key)
    {
        /** @var \MageSuite\SeoCategoryMetatagGeneration\Model\Metatag\MetatagInterface $metatag */
        $metatag = $this->metatagPool->getMetatag($key);

        if (empty($metatag)) {
            return null;
        }

        return $metatag->getText();
    }
}
