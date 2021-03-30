<?php

namespace Troytft\DataMapperBundle\Tests\DataTransformer;

use PHPUnit\Framework\TestCase;
use Troytft\DataMapperBundle\DataTransformer\ArrayOfDateTimeDataTransformer;

class ArrayOfDateTimeDataTransformerTest extends TestCase
{
    /**
     * @var ArrayOfDateTimeDataTransformer
     */
    private $transformer;

    public function setUp(): void
    {
        $this->transformer = new ArrayOfDateTimeDataTransformer();
        $this->transformer->setOptions(['propertyName' => 'propertyName']);
    }

    /**
     * @dataProvider validInputToExpectedResultProvider
     */
    public function testTransform(array $input, array $expectedResult)
    {
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
     */
    public function testInputValidation($invalidInput)
    {
        try {
            $this->transformer->transform($invalidInput);
            $this->fail();
        } catch (\Troytft\DataMapperBundle\Exception\ValidationFieldException $exception) {
            $this->assertEquals(400, $exception->getCode());
        }
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
