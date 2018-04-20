<?php

namespace Troytft\DataMapperBundle\Annotation;

/**
 * @Annotation
 */
class Float extends DataMapper
{
    /**
     * @return string
     */
    public function getType()
    {
        return 'float';
    }
}
