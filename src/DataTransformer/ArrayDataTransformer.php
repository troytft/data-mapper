<?php

namespace Troytft\DataMapperBundle\DataTransformer;

use Troytft\DataMapperBundle\Exception\ValidationFieldException;

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

    public static function getAlias(): string
    {
        return 'array';
    }
}
