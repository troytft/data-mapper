<?php

namespace Troytft\DataMapperBundle\Annotation;

/**
 * @Annotation
 */
class Integer extends DataMapper
{
    /**
     * @return string
     */
    public function getType()
    {
        return 'integer';
    }
}
