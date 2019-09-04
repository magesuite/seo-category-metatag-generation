<?php

namespace MageSuite\SeoCategoryMetatagGeneration\Model;

class MetatagPool
{
    protected $metatags;

    public function __construct(array $metatags)
    {
        $this->metatags = $metatags;
    }

    public function getMetatag($key)
    {
        foreach ($this->metatags as $metatag) {
            if (!$metatag->isApplicable($key)) {
                continue;
            }

            return $metatag;
        }

        return null;
    }
}
