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
     * @return string
     */
    public function getText($entityValue);
}
