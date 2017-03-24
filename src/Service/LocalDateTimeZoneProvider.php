<?php

namespace Troytft\DataMapperBundle\Service;

class LocalDateTimeZoneProvider
{
    /**
     * @var \DateTimeZone
     */
    private $localDateTimeZone;

    public function __construct()
    {
        $this->localDateTimeZone = (new \DateTime())->getTimezone();
    }

    /**
     * @return \DateTimeZone
     */
    public function getLocalDateTimeZone()
    {
        return $this->localDateTimeZone;
    }
}