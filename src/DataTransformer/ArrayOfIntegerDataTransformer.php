<?php

namespace Troytft\RequestMapperBundle\DataTransformer;

use Common\Exception\FormValidationFieldException;

class ArrayOfIntegerDataTransformer extends BaseDataTransformer implements DataTransformerInterface
{
    public function transform($array)
    {
        if (!is_array($array)) {
            throw new FormValidationFieldException($this->getPropertyName(), 'Значение должно быть массивом');
        }

        foreach ($array as $value) {
            if (!is_integer($value)) {
                throw new FormValidationFieldException($this->getPropertyName(), 'Значение должно быть числом');
            }
        }

        return $array;
    }
}