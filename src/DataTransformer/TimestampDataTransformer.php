<?php

namespace Troytft\DataMapperBundle\DataTransformer;

class TimestampDataTransformer extends BaseDataTransformer implements DataTransformerInterface
{
    public function transform($value)
    {
        return $value === null ?: (new \DateTime())->setTimestamp((int) $value);
    }
}
