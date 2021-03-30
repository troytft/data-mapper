<?php

namespace Troytft\DataMapperBundle\Helper;

use Troytft\DataMapperBundle\Exception\ValidationFieldException;

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
     * @return mixed
     */
    abstract protected function getWrongIsoFormatMessage();

    /**
     * @param string|null $value
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
            $dateTime = new \DateTime();
            $result->setTimezone($dateTime->getTimezone());
        }

        return $result;
    }
}
