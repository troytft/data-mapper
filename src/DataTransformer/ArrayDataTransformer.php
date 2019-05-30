<?php

namespace Troytft\DataMapperBundle\DataTransformer;

class ArrayDataTransformer extends BaseArrayDataTransformer implements DataTransformerInterface
{
    public function transform($value)
    {
        if ($value === null) {
            if ($this->isNullable) {
                return null;
            }
            if ($this->isForceArray) {
                return [];
            }
        }

        if (!is_array($value)) {
            throw new ValidationFieldException($this->getPropertyName(), 'Значение должно быть массивом');
        }

        return $value;
    }
}
