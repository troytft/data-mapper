<?php

namespace Troytft\DataMapperBundle\DataTransformer;

use Troytft\DataMapperBundle\Helper\DateTimeTransformerTrait;

class DateTimeDataTransformer extends BaseDataTransformer implements DataTransformerInterface
{
    use DateTimeTransformerTrait;

    const OPTION_SET_LOCAL_TIME_ZONE = 'setLocalTimeZone';

    const DATETIME_TRANSFORM_FORMAT = 'Y-m-d\TH:i:sP';

    const WRONG_DATETIME_FORMAT_ERROR_MESSAGE = 'Значение должно быть датой в формате YYYY-MM-DDThh:mm:ss±hh:mm';

    /**
     * @var bool
     */
    private $setLocalTimeZone = false;

    public function setOptions(array $options = []): void
    {
        parent::setOptions($options);

        if (array_key_exists(self::OPTION_SET_LOCAL_TIME_ZONE, $options)) {
            $this->setLocalTimeZone = $options[self::OPTION_SET_LOCAL_TIME_ZONE];
        }
    }

    public function transform($value)
    {
        return $this->transformStringToDateTime($value);
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
     * @return mixed
     */
    protected function getWrongIsoFormatMessage()
    {
        return self::WRONG_DATETIME_FORMAT_ERROR_MESSAGE;
    }

    public static function getAlias(): string
    {
        return 'datetime';
    }
}
