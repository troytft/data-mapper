<?php

namespace Troytft\DataMapperBundle\Annotation;

/**
 * @Annotation
 */
class BooleanType extends DataMapper
{
    /**
     * @return string
     */
    public function getType()
    {
        return 'boolean';
    }
}
