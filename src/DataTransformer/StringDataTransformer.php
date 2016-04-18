<?php

namespace Troytft\DataMapperBundle\DataTransformer;

class StringDataTransformer extends BaseDataTransformer implements DataTransformerInterface
{
    public function transform($value)
    {
        return $value === null || trim($value) === "" ? null : (string) $value;
    }
}