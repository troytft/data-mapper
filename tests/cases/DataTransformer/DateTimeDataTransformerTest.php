<?php

use PHPUnit\Framework\TestCase;
use Troytft\DataMapperBundle\DataTransformer\DateTimeDataTransformer;

class DateTimeDataTransformerTest extends TestCase
{
    /**
     * @var DateTimeDataTransformer
     */
    private $transformer;

    public function setUp(): void
    {
        $this->transformer = new DateTimeDataTransformer();
        $this->transformer->setOptions(['propertyName' => 'propertyName']);
    }

    /**
     * @dataProvider validInputToExpectedResultProvider
     */
    public function testTransform($input, \DateTime $expectedResult = null)
    {
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
