<?php

namespace Troytft\DataMapperBundle\Tests\DataTransformer;

use Troytft\DataMapperBundle\DataTransformer\DateTimeDataTransformer;
use Troytft\DataMapperBundle\Service\LocalDateTimeZoneProvider;

class DateTimeDataTransformerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var LocalDateTimeZoneProvider|\PHPUnit_Framework_MockObject_MockObject
     */
    private $localTimeZoneProviderMock;

    /**
     * @var DateTimeDataTransformer
     */
    private $transformer;

    public function setup()
    {
        $this->localTimeZoneProviderMock = $this->getMockBuilder(LocalDateTimeZoneProvider::class)
            ->disableOriginalConstructor()
            ->setMethods([
                'getLocalDateTimeZone'
            ])
            ->getMock();

        $this->transformer = new DateTimeDataTransformer($this->localTimeZoneProviderMock);
    }

    /**
     * @dataProvider validInputToExpectedResultProvider
     */
    public function testTransform($input, \DateTime $expectedResult = null)
    {
        $this->localTimeZoneProviderMock->expects($this->never())
            ->method('getLocalDateTimeZone');

        $result = $this->transformer->transform($input);

        if (is_null($expectedResult)) {
            $this->assertEquals($expectedResult, $result);
        } else {
            $this->assertEquals(
                $expectedResult->getTimestamp(),
                $result->getTimestamp(),
                'Expected timestamp does not match actual timestamp'
            );

            $this->assertEquals(
                $expectedResult->format('P'),
                $result->format('P'),
                'Expected UTC offset does not match actual UTC offset'
            );
        }
    }

    /**
     * @dataProvider differentTimeZonesValueToResultProvider
     */
    public function testTransformToLocalTimeZone(\DateTimeZone $localTimeZone, $input, \DateTime $expectedResult = null)
    {
        $options = $this->transformer->getOptions();
        $options['setLocalTimeZone'] = true;
        $this->transformer->setOptions($options);

        $this->localTimeZoneProviderMock->expects($this->once())
            ->method('getLocalDateTimeZone')
            ->willReturn($localTimeZone);

        $result = $this->transformer->transform($input);

        if (is_null($expectedResult)) {
            $this->assertEquals($expectedResult, $result);
        } else {
            $this->assertEquals(
                $expectedResult->getTimeStamp(),
                $result->getTimestamp(),
                'Expected timestamp does not match actual timestamp'
            );
            $this->assertEquals(
                $expectedResult->format('P'),
                $result->format('P'),
                'Expected UTC offset does not match actual UTC offset'
            );
        }
    }

    /**
     * @dataProvider invalidInputProvider
     *
     * @expectedException \Troytft\DataMapperBundle\Exception\ValidationFieldException
     * @expectedExceptionCode 400
     */
    public function testInputValidation($invalidInput)
    {
        $this->transformer->transform($invalidInput);
    }

    /**
     * @return array
     */
    public function validInputToExpectedResultProvider()
    {
        // ex.: +03:00
        $localTimezone = (new \DateTime())->format('P');

        return [
            [
                '2017-03-23T00:00:00'.$localTimezone,
                new \DateTime('23 march 2017'),
            ],
            [
                null,
                null
            ]
        ];
    }

    /**
     * @return array
     */
    public function differentTimeZonesValueToResultProvider()
    {
        // local time zone for mock
        $timeZoneUtc = new \DateTimeZone('+00:00');
        $timeZoneNewYork = new \DateTimeZone('-04:00');
        $timeZoneMoscow = new \DateTimeZone('+03:00');

        return [
            // 5 am of 23 May in UTC+5 is midnight of 23 May in UTC
            [
                $timeZoneUtc,
                '2017-03-23T05:00:00+05:00',
                new \DateTime('23 march 2017', $timeZoneUtc)
            ],
            // input time zone is UTC +5; 9 pm of 23th May in UTC +5 corresponds to midnight of 23rd May in UTC -4
            [
                $timeZoneNewYork,
                '2017-03-23T09:00:00+05:00',
                new \DateTime('23 march 2017', $timeZoneNewYork)
            ],
            // input time zone is UTC +5; 2 am in UTC +5 corresponds to midnight in UTC +3
            [
                $timeZoneMoscow,
                '2017-03-23T02:00:00+05:00',
                new \DateTime('23 march 2017', $timeZoneMoscow)
            ]
        ];
    }

    /**
     * @return array
     */
    public function invalidInputProvider()
    {
        return [
            [
                1495486800
            ],
            [
                '2017-05-23'
            ],
            [
                'invalid input'
            ],
            [
                false
            ]
        ];
    }
}