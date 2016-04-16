<?php

namespace Troytft\RequestMapperBundle\DataTransformer;

class ArrayDataTransformer extends BaseDataTransformer implements DataTransformerInterface
{
    public function transform($value)
    {
        return $value === null ? [] : (array) $value;
    }
}