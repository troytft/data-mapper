<?php

namespace Troytft\DataMapperBundle\Annotation;

/**
 * @Annotation
 */
class IntegerType extends DataMapper
{
    /**
     * @return string
     */
    public function getType()
    {
        return 'integer';
    }
}
