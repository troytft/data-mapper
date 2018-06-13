<?php

namespace Troytft\DataMapperBundle\DataTransformer;

class BooleanDataTransformer extends BaseDataTransformer implements DataTransformerInterface
{
    public function transform($value)
    {
        if ($value === null) {
            return null;
        }

        return filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
    }
}
