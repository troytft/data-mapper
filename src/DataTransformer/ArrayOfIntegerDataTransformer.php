<?php

namespace Troytft\DataMapperBundle\DataTransformer;

use Troytft\DataMapperBundle\Exception\ValidationFieldException;

class ArrayOfIntegerDataTransformer extends BaseDataTransformer implements DataTransformerInterface
{
    public function transform($array)
    {
        if (!is_array($array)) {
            throw new ValidationFieldException($this->getPropertyName(), 'Значение должно быть массивом');
        }

        foreach ($array as $value) {
            if (!is_integer($value)) {
                throw new ValidationFieldException($this->getPropertyName(), 'Значение должно быть числом');
            }
        }

        return $array;
    }
}