<?php

namespace Troytft\DataMapperBundle\DataTransformer;

use Troytft\DataMapperBundle\Exception\ValidationFieldException;

class ArrayOfIntegerDataTransformer extends BaseArrayDataTransformer implements DataTransformerInterface
{
    /**
     * @var bool
     */
    protected $isForceArray = true;

    public function transform($array)
    {
        if ($array === null) {
            if ($this->isNullable) {
                return null;
            }
            if ($this->isForceArray) {
                $array = [];
            }
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

    public static function getAlias(): string
    {
        return 'array_of_integer';
    }
}
