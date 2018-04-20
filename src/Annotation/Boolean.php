<?php

namespace Troytft\DataMapperBundle\Annotation;

/**
 * @Annotation
 */
class Boolean extends DataMapper
{
    /**
     * @return string
     */
    public function getType()
    {
        return 'boolean';
    }
}
