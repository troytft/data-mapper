<?php

namespace Troytft\DataMapperBundle\DataTransformer;

use Troytft\DataMapperBundle\Helper\DateTimeTransformerTrait;
use Troytft\DataMapperBundle\Exception\ValidationFieldException;
use Troytft\DataMapperBundle\Service\LocalDateTimeZoneProvider;

class ArrayOfDateTimeDataTransformer extends BaseDataTransformer implements DataTransformerInterface
{
    use DateTimeTransformerTrait;

    const OPTION_SET_LOCAL_TIME_ZONE = 'setLocalTimeZone';

    const DATETIME_TRANSFORM_FORMAT = 'Y-m-d\TH:i:sP';

    const WRONG_DATETIME_FORMAT_ERROR_MESSAGE = 'Значения массива должны быть датами в формате YYYY-MM-DDThh:mm:ss±hh:mm';

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

    public function setOptions(array $options = [])
    {
        parent::setOptions($options);

        if (array_key_exists(self::OPTION_SET_LOCAL_TIME_ZONE, $options)) {
            $this->setLocalTimeZone = $options[self::OPTION_SET_LOCAL_TIME_ZONE];
        }
    }

    public function transform($array)
    {
        if (!is_array($array)) {
            throw new ValidationFieldException($this->getPropertyName(), 'Значение должно быть массивом');
        }

        $result = [];

        foreach ($array as $value) {
            $transformedValue = $this->transformStringToDateTime($value);

            if (is_null($transformedValue)) {
                throw new ValidationFieldException($this->getPropertyName(), 'Значения массива не должны быть пустыми');
            }

            $result[] = $transformedValue;
        }

        return $result;
    }

    /**
     * @return string
     */
    protected function getTransformFormat()
    {
        return self::DATETIME_TRANSFORM_FORMAT;
    }

    /**
     * @return bool
     */
    protected function getShouldSetLocalTimeZone()
    {
        return $this->setLocalTimeZone;
    }

    /**
     * @return LocalDateTimeZoneProvider
     */
    protected function getTimeZoneProvider()
    {
        return $this->timeZoneProvider;
    }

    /**
     * @return mixed
     */
    protected function getWrongIsoFormatMessage()
    {
        return self::WRONG_DATETIME_FORMAT_ERROR_MESSAGE;
    }
}
