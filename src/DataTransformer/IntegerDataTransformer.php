<?php

namespace Troytft\RequestMapperBundle\DataTransformer;

use Common\Exception\FormValidationFieldException;

class IntegerDataTransformer extends BaseDataTransformer implements DataTransformerInterface
{
    public function transform($value)
    {
        if ($value === null) {
            return null;
        }

        if (!is_numeric($value)) {
            throw new FormValidationFieldException($this->getPropertyName(), 'Значение должно быть числом');
        }

        return (int) $value;
    }
}