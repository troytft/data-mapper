<?php

namespace Troytft\DataMapperBundle\Annotation;

/**
 * @Annotation
 */
class String extends DataMapper
{
    /**
     * @return string
     */
    public function getType()
    {
        return 'string';
    }
}
