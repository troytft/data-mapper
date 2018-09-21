<?php

namespace Troytft\DataMapperBundle\DataTransformer;

use Troytft\DataMapperBundle\Exception\ValidationFieldException;

class ArrayOfIntegerDataTransformer extends BaseDataTransformer implements DataTransformerInterface
{
    /**
     * @var bool
     */
    private $nullable = false;

    /**
     * {@inheritdoc}
     */
    public function setOptions(array $value = [])
    {
        parent::setOptions($value);

        if (\array_key_exists('nullable', $value)) {
            $this->nullable = $value['nullable'];
        }
    }

    public function transform($array)
    {
        if ($this->nullable && $array === null) {
            $array = [];
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
