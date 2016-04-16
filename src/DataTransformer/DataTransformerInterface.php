<?php

namespace Troytft\RequestMapperBundle\DataTransformer;

interface DataTransformerInterface
{
    public function transform($value);
    public function setOptions($options);
}