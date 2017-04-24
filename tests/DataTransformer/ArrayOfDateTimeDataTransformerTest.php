<?php

namespace Troytft\DataMapperBundle\Tests\DataTransformer;

use Troytft\DataMapperBundle\DataTransformer\ArrayOfDateTimeDataTransformer;
use Troytft\DataMapperBundle\Service\LocalDateTimeZoneProvider;

class ArrayOfDateTimeDataTransformerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var LocalDateTimeZoneProvider|\PHPUnit_Framework_MockObject_MockObject
     */
    private $localTimeZoneProviderMock;

    /**
     * @var ArrayOfDateTimeDataTransformer
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

        $this->transformer = new ArrayOfDateTimeDataTransformer($this->localTimeZoneProviderMock);
    }

    /**
     * @dataProvider validInputToExpectedResultProvider
     */
    public function testTransform(array $input, array $expectedResult)
    {
        $this->localTimeZoneProviderMock->expects($this->never())
            ->method('getLocalDateTimeZone');

        $result = $this->transformer->transform($input);

        $intersect = array_uintersect($result, $expectedResult, function(\DateTime $resultItem, \DateTime $expectedResultItem) {
            if ($resultItem->getTimestamp() === $expectedResultItem->getTimestamp()) {
                return 0;
            }

            if ($resultItem->getTimestamp() > $expectedResultItem->getTimestamp()) {
                return 1;
            }

            return -1;
        });

        $this->assertEquals(count($expectedResult), count($intersect), 'Some of values were transformed incorrectly');
    }

    /**
     * @dataProvider differentTimeZonesValueToResultProvider
     */
    public function testTransformToLocalTimeZone(\DateTimeZone $localTimeZone, array $input, array $expectedResult)
    {
        $options = $this->transformer->getOptions();
        $options['setLocalTimeZone'] = true;
        $this->transformer->setOptions($options);

        $this->localTimeZoneProviderMock->expects($this->once())
            ->method('getLocalDateTimeZone')
            ->willReturn($localTimeZone);

        $result = $this->transformer->transform($input);

        $intersect = array_uintersect($result, $expectedResult, function(\DateTime $resultItem, \DateTime $expectedResultItem) {
            if ($resultItem->getTimestamp() === $expectedResultItem->getTimestamp()) {
                return 0;
            }

            if ($resultItem->getTimestamp() > $expectedResultItem->getTimestamp()) {
                return 1;
            }

            return -1;
        });

        $this->assertEquals(count($expectedResult), count($intersect), 'Some of values were transformed incorrectly');
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
                [
                    '2017-03-23T00:00:00'.$localTimezone,
                    '2017-03-24T00:00:00'.$localTimezone,
                ],
                [
                    new \DateTime('23 march 2017'),
                    new \DateTime('24 march 2017'),
                ]
            ],
            [
                [],
                []
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
                [
                    '2017-03-23T05:00:00+05:00'
                ],
                [
                    new \DateTime('23 march 2017', $timeZoneUtc)
                ]
            ],
            // input time zone is UTC +5; 9 pm of 23th May in UTC +5 corresponds to midnight of 23rd May in UTC -4
            [
                $timeZoneNewYork,
                [
                    '2017-03-23T09:00:00+05:00'
                ],
                [
                    new \DateTime('23 march 2017', $timeZoneNewYork)
                ]
            ],
            // input time zone is UTC +5; 2 am in UTC +5 corresponds to midnight in UTC +3
            [
                $timeZoneMoscow,
                [
                    '2017-03-23T02:00:00+05:00'
                ],
                [
                    new \DateTime('23 march 2017', $timeZoneMoscow)
                ]
            ]
        ];
    }

    /**
     * First is valid, second is invalid
     * @return array
     */
    public function invalidInputProvider()
    {
        return [
            [
                [
                    '2017-03-23T02:00:00+05:00',
                    1495486800
                ]
            ],
            [
                [
                    '2017-03-23T02:00:00+05:00',
                    '2017-05-23'
                ]
            ],
            [
                [
                    '2017-03-23T02:00:00+05:00',
                    'invalid input'
                ]
            ],
            [
                [
                    '2017-03-23T02:00:00+05:00',
                    false
                ]
            ],
            [
                [
                    '2017-03-23T02:00:00+05:00',
                    null
                ]
            ],
            [
                'not an array value'
            ]
        ];
    }
}