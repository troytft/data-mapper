<?php

namespace Troytft\DataMapperBundle\DataTransformer;

use Troytft\DataMapperBundle\Exception\ValidationFieldException;
use Troytft\DataMapperBundle\Service\LocalDateTimeZoneProvider;

class DateTimeDataTransformer extends BaseDataTransformer implements DataTransformerInterface
{
    const OPTION_SET_LOCAL_TIME_ZONE = 'setLocalTimeZone';

    const DATETIME_TRANSFORM_FORMAT = 'Y-m-d\TH:i:sP';

    const ERROR_MESSAGE = 'Значение должно быть датой в формате YYYY-MM-DDThh:mm:ss±hh:mm';

    /**
     * @var LocalDateTimeZoneProvider
     */
    private $timeZoneProvider;

    /**
     * @var bool
     */
    private $setLocalTimeZone = false;

    /**
     * @param LocalDateTimeZoneProvider $timeZoneProvider
     */
    public function __construct(LocalDateTimeZoneProvider $timeZoneProvider)
    {
        $this->timeZoneProvider = $timeZoneProvider;
    }

    public function setOptions($options)
    {
        parent::setOptions($options);

        if (array_key_exists(self::OPTION_SET_LOCAL_TIME_ZONE, $options)) {
            $this->setLocalTimeZone = $options[self::OPTION_SET_LOCAL_TIME_ZONE];
        }
    }

    public function transform($value)
    {
        if (is_null($value)) {
            return null;
        }

        $result = \DateTime::createFromFormat(self::DATETIME_TRANSFORM_FORMAT, $value);

        if ($result === false) {
            throw new ValidationFieldException($this->getPropertyName(), self::ERROR_MESSAGE);
        }

        if ($this->setLocalTimeZone) {
            $result->setTimezone($this->timeZoneProvider->getLocalDateTimeZone());
        }

        return $result;
    }
}