<?php

namespace Troytft\DataMapperBundle\DataTransformer;

class PlainDataTransformer extends BaseDataTransformer implements DataTransformerInterface
{
    public function transform($value)
    {
        return $value;
    }

    public static function getAlias(): string
    {
        return 'plain';
    }
}
