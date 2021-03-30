<?php

namespace Troytft\DataMapperBundle\DataTransformer;

class TimestampDataTransformer extends BaseDataTransformer implements DataTransformerInterface
{
    public function transform($value)
    {
        return $value === null ? null : (new \DateTime('now'))->setTimestamp($value);
    }

    public static function getAlias(): string
    {
        return 'timestamp';
    }
}
