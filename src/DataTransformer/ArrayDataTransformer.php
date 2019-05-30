<?php

namespace Troytft\DataMapperBundle\DataTransformer;

class ArrayDataTransformer extends BaseArrayDataTransformer implements DataTransformerInterface
{
    public function transform($value)
    {
        if ($value === null) {
            if ($this->isNullable()) {
                return null;
            } else {
                return [];
            }
        }

        return (array) $value;
    }
}
