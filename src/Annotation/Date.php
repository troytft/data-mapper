<?php

namespace Troytft\DataMapperBundle\Annotation;

/**
 * @Annotation
 */
class Date extends DataMapper
{
    /**
     * @return string
     */
    public function getType()
    {
        return 'date';
    }
}
