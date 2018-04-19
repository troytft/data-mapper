<?php

namespace Troytft\DataMapperBundle\Annotation;

/**
 * @Annotation
 */
class DateType extends DataMapper
{
    /**
     * @return string
     */
    public function getType()
    {
        return 'date';
    }
}
