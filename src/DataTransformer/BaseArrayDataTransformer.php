<?php

namespace Troytft\DataMapperBundle\DataTransformer;

abstract class BaseArrayDataTransformer extends BaseDataTransformer
{
    const NULLABLE_OPTION = 'nullable';
    const FORCE_ARRAY_OPTION = 'force_array';

    /**
     * @var bool
     */
    protected $isNullable = false;

    /**
     * @var bool
     */
    protected $isForceArray = true;

    public function setOptions(array $value = [])
    {
        if (isset($value[static::NULLABLE_OPTION])) {
            $this->isNullable = $value[static::NULLABLE_OPTION];
        }

        if (isset($value[static::FORCE_ARRAY_OPTION])) {
            $this->isForceArray = $value[static::FORCE_ARRAY_OPTION];
        }

        parent::setOptions($value);
    }
}
