<?php

namespace Troytft\DataMapperBundle\Annotation;

/**
 * @Annotation
 */
class DataMapper
{
    protected $type;
    protected $name;
    protected $groups;
    protected $options;

    public function __construct($options = [])
    {
        $this->type = isset($options['type']) ? $options['type'] : 'string';
        $this->name = isset($options['name']) ? $options['name'] : null;
        $this->groups = isset($options['groups']) ? $options['groups'] : ['Default'];
        $this->options = isset($options['options']) ? $options['options'] : [];
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $value
     */
    public function setType($value)
    {
        $this->type = $value;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getGroups()
    {
        return $this->groups;
    }

    /**
     * @param mixed $value
     */
    public function setGroups($value)
    {
        $this->groups = $value;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $value
     */
    public function setName($value)
    {
        $this->name = $value;

        return $this;
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @param array $value
     */
    public function setOptions($value)
    {
        $this->options = $value;

        return $this;
    }
}
