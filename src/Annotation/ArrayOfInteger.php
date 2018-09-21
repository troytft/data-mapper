<?php

namespace Troytft\DataMapperBundle\Annotation;

/**
 * @Annotation
 */
class ArrayOfInteger extends DataMapper
{
    /**
     * @return string
     */
    public function getType()
    {
        return 'array_of_integer';
    }

    /**
     * @inheritdoc
     */
    public function getOptions()
    {
        return array_merge(parent::getOptions(), [
            'nullable' => true
        ]);
    }
}
