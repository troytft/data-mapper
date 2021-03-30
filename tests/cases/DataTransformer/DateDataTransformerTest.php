<?php

use PHPUnit\Framework\TestCase;
use Troytft\DataMapperBundle\DataTransformer\DateDataTransformer;

class DateDataTransformerTest extends TestCase
{
    /**
     * @var DateDataTransformer
     */
    private $transformer;

    public function setUp(): void
    {
        $this->transformer = new DateDataTransformer();
        $this->transformer->setOptions(['propertyName' => 'propertyName']);
    }

    /**
     * @dataProvider validInputToExpectedResultProvider
     */
    public function testTransform($input, $expectedResult)
    {
        $result = $this->transformer->transform($input);

        $this->assertEquals($expectedResult, $result);
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
        return [
            [
                '2017-03-23',
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
                '2017-03-23T00:00:00+03:00'
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
