<?php

namespace Troytft\RequestMapperBundle\DataTransformer;

class PlainDataTransformer extends BaseDataTransformer implements DataTransformerInterface
{
    public function transform($value)
    {
        return $value;
    }
}