<?php

namespace Troytft\DataMapperBundle\Annotation;

/**
 * @Annotation
 */
class FloatType extends DataMapper
{
    /**
     * @return string
     */
    public function getType()
    {
        return 'float';
    }
}
