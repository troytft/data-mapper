<?php

namespace Troytft\DataMapperBundle\Exception;

class ValidationException extends BaseException
{
    /**
     * @var array
     */
    protected $errors;

    public function __construct($errors = [])
    {
        parent::__construct(json_encode(['errors' => $errors]), 400);

        $this->errors = $errors;
    }

    /**
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * @param array $value
     */
    public function setErrors($value)
    {
        $this->errors = $value;

        return $this;
    }
}