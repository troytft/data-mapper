<?php

namespace Troytft\DataMapperBundle\DataTransformer;

use Troytft\DataMapperBundle\Exception\BaseException;

abstract class BaseArrayDataTransformer extends BaseDataTransformer
{
    const NULLABLE_OPTION = 'nullable';

    /**
     * @var bool
     */
    private $isNullable = false;

    /**
     * {@inheritDoc}
     */
    public function setOptions(array $value = [])
    {
        if (isset($value[static::NULLABLE_OPTION])) {
            $this->isNullable = $value[static::NULLABLE_OPTION];
        }

        return parent::setOptions($value);
    }

    /**
     * @return bool
     */
    public function isNullable(): bool
    {
        return $this->isNullable;
    }
}
