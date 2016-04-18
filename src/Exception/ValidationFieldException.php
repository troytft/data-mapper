<?php

namespace Troytft\DataMapperBundle\Exception;

class ValidationFieldException extends ValidationException
{
    public function __construct($fieldName, $errorMessage)
    {
        parent::__construct([(string) $fieldName => (array) $errorMessage]);
    }
}