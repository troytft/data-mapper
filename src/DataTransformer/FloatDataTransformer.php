<?php

namespace Troytft\DataMapperBundle\DataTransformer;

use Troytft\DataMapperBundle\Exception\ValidationFieldException;

class FloatDataTransformer extends BaseDataTransformer implements DataTransformerInterface
{
    public function transform($value)
    {
        if ($value === null) {
            return null;
        }

        if (!is_numeric($value)) {
            throw new ValidationFieldException($this->getPropertyName(), 'Значение должно быть числом');
        }

        return (float) $value;
    }
}