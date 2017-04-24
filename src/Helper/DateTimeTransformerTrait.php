<?php

namespace Troytft\DataMapperBundle\Helper;

use Troytft\DataMapperBundle\Exception\ValidationFieldException;
use Troytft\DataMapperBundle\Service\LocalDateTimeZoneProvider;

trait DateTimeTransformerTrait
{
    /**
     * @return string
     */
    abstract protected function getTransformFormat();

    /**
     * @return string
     */
    abstract public function getPropertyName();

    /**
     * @return bool
     */
    abstract protected function getShouldSetLocalTimeZone();

    /**
     * @return LocalDateTimeZoneProvider
     */
    abstract protected function getTimeZoneProvider();

    /**
     * @return mixed
     */
    abstract protected function getWrongIsoFormatMessage();

    /**
     * @param $value
     *
     * @return \DateTime|null
     * @throws ValidationFieldException
     */
    public function transformStringToDateTime($value)
    {
        if (is_null($value)) {
            return null;
        }

        $result = \DateTime::createFromFormat($this->getTransformFormat(), $value);

        if ($result === false) {
            throw new ValidationFieldException($this->getPropertyName(), $this->getWrongIsoFormatMessage());
        }

        if ($this->getShouldSetLocalTimeZone()) {
            $result->setTimezone($this->getTimeZoneProvider()->getLocalDateTimeZone());
        }

        return $result;
    }
}