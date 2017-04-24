<?php

namespace Troytft\DataMapperBundle\DataTransformer;

use Troytft\DataMapperBundle\Exception\ValidationFieldException;

class DateDataTransformer extends BaseDataTransformer implements DataTransformerInterface
{
    const ERROR_MESSAGE = 'Значение должно быть датой в формате YYYY-MM-DD';

    public function transform($value)
    {
        if (is_null($value)) {
            return null;
        }

        $result = \DateTime::createFromFormat('Y-m-d', $value);

        if ($result === false) {
            throw new ValidationFieldException($this->getPropertyName(), self::ERROR_MESSAGE);
        }

        return $result->setTime(0, 0, 0);
    }
}