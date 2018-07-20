<?php

namespace Troytft\DataMapperBundle\Annotation;

/**
 * @Annotation
 */
class ArrayOfDateTimeType extends DataMapper
{
    /**
     * @return string
     */
    public function getType()
    {
        return 'array_of_datetime';
    }

    /**
     * @inheritdoc
     */
    public function getOptions()
    {
        return array_merge(parent::getOptions(), [
            'setLocalTimeZone' => true
        ]);
    }
}
