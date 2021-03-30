<?php

namespace Troytft\DataMapperBundle\DataTransformer;

use Troytft\DataMapperBundle\Exception\ValidationFieldException;

class BooleanDataTransformer extends BaseDataTransformer implements DataTransformerInterface
{
    public function transform($value)
    {
        if ($value === null) {
            return null;
        }

        $val = filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
        
        if ($val === null) {
            throw new ValidationFieldException($this->getPropertyName(), 'Значение должно быть булевым');
        }
        
        return $val;
    }

    public static function getAlias(): string
    {
        return 'boolean';
    }
}
