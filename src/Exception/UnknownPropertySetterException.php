<?php

namespace Troytft\DataMapperBundle\Exception;

class UnknownPropertySetterException extends BaseException
{
    /**
     * @var string
     */
    protected $propertyName;

    public function __construct($propertyName)
    {
        $this->propertyName = $propertyName;

        parent::__construct(sprintf('Cant find setter for property "%s"', $propertyName));
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