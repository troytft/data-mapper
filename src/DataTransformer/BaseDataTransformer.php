<?php

namespace Troytft\DataMapperBundle\DataTransformer;

use Troytft\DataMapperBundle\Exception\BaseException;

abstract class BaseDataTransformer implements DataTransformerInterface
{
    const PROPERTY_NAME_OPTION = 'propertyName';

    /**
     * @var array
     */
    protected $options = [];

    /**
     * @return array
     */
    public function getOptions()
    {
        return (array) $this->options;
    }

    /**
     * @param array $value
     */
    public function setOptions(array $value = [])
    {
        $this->options = $value;

        return $this;
    }

    /**
     * @param mixed $value
     * @return mixed
     */
    public function transform($value)
    {
        return $value;
    }

    /**
     * @return string
     */
    public function getPropertyName()
    {
        if (!isset($this->getOptions()[self::PROPERTY_NAME_OPTION])) {
            throw new BaseException('Property name must be defined!');
        }

        return (string) $this->getOptions()[self::PROPERTY_NAME_OPTION];
    }
}