<?php

namespace Troytft\DataMapperBundle\DataTransformer;

use Troytft\DataMapperBundle\Exception\ValidationFieldException;

class ArrayOfIntegerDataTransformer extends BaseArrayDataTransformer implements DataTransformerInterface
{
    public function transform($array)
    {
        if ($this->isNullable() && $array === null) {
            return null;
        }

        if (!is_array($array)) {
            throw new ValidationFieldException($this->getPropertyName(), 'Значение должно быть массивом');
        }

        foreach ($array as &$value) {
            if (!is_numeric($value)) {
                throw new ValidationFieldException($this->getPropertyName(), 'Значение должно быть числом');
            }

            $value = (int) $value;
        }

        return $array;
    }
}
