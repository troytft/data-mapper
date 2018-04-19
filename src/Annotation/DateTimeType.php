<?php

namespace Troytft\DataMapperBundle\Annotation;

/**
 * @Annotation
 */
class DateTimeType extends DataMapper
{
    /**
     * @return string
     */
    public function getType()
    {
        return 'datetime';
    }

    /**
     * @inheritdoc
     */
    public function getOptions()
    {
        return array_map(parent::getOptions(), [
            'setLocalTimeZone' => true
        ]);
    }
}
