<?php

namespace Troytft\DataMapperBundle\Exception;

class UnknownPropertyException extends BaseException
{
    /**
     * @var string
     */
    protected $propertyName;

    public function __construct($propertyName)
    {
        $this->propertyName = $propertyName;
        
        parent::__construct(sprintf('Unknown property with name "%s"', $propertyName));
    }

    /**
     * @return string
     */
    public function getPropertyName()
    {
        return $this->propertyName;
    }

    /**
     * @param string $value
     */
    public function setPropertyName($value)
    {
        $this->propertyName = $value;

        return $this;
    }
}