<?php

namespace Troytft\DataMapperBundle\DataTransformer;

class StringDataTransformer extends BaseDataTransformer implements DataTransformerInterface
{
    const TRIM_OPTION = 'trim';

    public function isTrimMode()
    {
        if (!array_key_exists(self::TRIM_OPTION, $this->getOptions())) {
            return false;
        }

        return (bool) $this->getOptions()[self::TRIM_OPTION];
    }

    /**
     * @param mixed $value
     * @return null|string
     */
    public function transform($value)
    {
        if ($value === null || trim($value) === "") {
            return null;
        }

        if ($this->isTrimMode()) {
            return trim($value);
        }

        return (string) $value;
    }

    public static function getAlias(): string
    {
        return 'string';
    }
}
