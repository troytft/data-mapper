<?php

namespace Troytft\DataMapperBundle\Tests\DataTransformer;

use Troytft\DataMapperBundle\DataTransformer\DateDataTransformer;

class DateDataTransformerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var DateDataTransformer
     */
    private $transformer;

    public function setup()
    {
        $this->transformer = new DateDataTransformer();
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
        return [
            [
                '2017-05-23',
                new \DateTime('23 may 2017'),
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
                '2017-05-23T00:00:00+03:00'
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