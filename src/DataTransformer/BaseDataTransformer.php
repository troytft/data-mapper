<?php

namespace Troytft\DataMapperBundle\DataTransformer;

use Troytft\DataMapperBundle\Exception\BaseException;

abstract class BaseDataTransformer implements DataTransformerInterface
{
    const PROPERTY_NAME_OPTION = 'propertyName';
    const MODEL_OPTION = 'model';
    const MODEL_REFLECTION_OPTION = 'modelReflection';

    /**
     * @var array
     */
    protected $options = [];

    /**
     * @var object|null
     */
    private $model;

    /**
     * @var \ReflectionClass|null
     */
    private $modelReflection;

    /**
     * @return array
     */
    public function getOptions()
    {
        return (array) $this->options;
    }

    public function setOptions(array $value = [])
    {
        if (isset($value[static::MODEL_OPTION])) {
            $this->model = $value[static::MODEL_OPTION];
        }
        if (isset($value[static::MODEL_REFLECTION_OPTION])) {
            $this->modelReflection = $value[static::MODEL_REFLECTION_OPTION];
        }

        $this->options = $value;
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

    /**
     * @return object
     */
    public function getModel()
    {
        if (is_null($this->model)) {
            throw new BaseException('Model is not defined');
        }

        return $this->model;
    }

    /**
     * @return \ReflectionClass
     */
    public function getModelReflection()
    {
        if (is_null($this->model)) {
            throw new BaseException('ModelReflection is not defined');
        }

        return $this->modelReflection;
    }
}
