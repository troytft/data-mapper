<?php

namespace TestApp\Model;

use Troytft\DataMapperBundle\Annotation as DataMapper;

class TestModel
{
    /**
     * @var \DateTime|null
     *
     * @DataMapper\DateType()
     */
    private $dateType;

    /**
     * @var string|null
     *
     * @DataMapper\StringType()
     */
    private $stringType;

    public function getDateType(): ?\DateTime
    {
        return $this->dateType;
    }

    public function setDateType(?\DateTime $dateType)
    {
        $this->dateType = $dateType;

        return $this;
    }

    public function getStringType(): ?string
    {
        return $this->stringType;
    }

    public function setStringType(?string $stringType)
    {
        $this->stringType = $stringType;

        return $this;
    }
}
