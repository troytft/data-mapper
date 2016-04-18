<?php

namespace Troytft\DataMapperBundle\Exception;

class UnknownDataTransformerException extends BaseException
{
    public function __construct($alias)
    {
        parent::__construct(sprintf('Unknown data transformer with alias "%s"', $alias));
    }
}