<?php

namespace Troytft\DataMapperBundle\Annotation;

/**
 * @Annotation
 */
class StringType extends DataMapper
{
    /**
     * @return string
     */
    public function getType()
    {
        return 'string';
    }
}
