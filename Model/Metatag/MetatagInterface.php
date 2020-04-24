<?php

namespace MageSuite\SeoCategoryMetatagGeneration\Model\Metatag;

interface MetatagInterface
{
    /**
     * @param string $key
     * @return bool
     */
    public function isApplicable($key);

    /**
     * @param string $entityValue
     * @return string
     */
    public function getText($entityValue);
}
