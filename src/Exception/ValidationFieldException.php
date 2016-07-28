<?php

namespace Troytft\DataMapperBundle\Exception;

class ValidationFieldException extends ValidationException
{
    public function __construct($fieldName, $error)
    {
        parent::__construct([(string) $fieldName => (array) $error], 400);
    }
}