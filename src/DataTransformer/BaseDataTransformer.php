<?php

namespace Troytft\DataMapperBundle\DataTransformer;

class BaseDataTransformer implements DataTransformerInterface
{
    protected $options;

    /**
     * @return mixed
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @param mixed $value
     */
    public function setOptions($value)
    {
        $this->options = $value;

        return $this;
    }

    public function transform($value)
    {
        return $value;
    }

    public function getPropertyName()
    {
        return $this->options['propertyName'];
    }
}