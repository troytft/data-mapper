<?php

namespace Troytft\DataMapperBundle\DataTransformer;

interface DataTransformerInterface
{
    public function transform($value);
    public function setOptions($options);
}