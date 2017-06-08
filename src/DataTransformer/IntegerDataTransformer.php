<?php

namespace Troytft\DataMapperBundle\DataTransformer;

use Troytft\DataMapperBundle\Exception\ValidationFieldException;

class IntegerDataTransformer extends BaseDataTransformer implements DataTransformerInterface
{
    private $emptyStringIsNull;

    public function setOptions($options)
    {
        $this->emptyStringIsNull = !empty($options['emptyStringIsNull']);
        parent::setOptions($options);
    }

    public function transform($value)
    {
        if ($value === null || ($this->emptyStringIsNull && $value === '')) {
            return null;
        }

        if (!is_numeric($value)) {
            throw new ValidationFieldException($this->getPropertyName(), 'Значение должно быть числом');
        }

        return (int) $value;
    }
}