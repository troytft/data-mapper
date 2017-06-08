<?php

namespace DataTransformer;

use Troytft\DataMapperBundle\DataTransformer\StringDataTransformer;

class StringDataTransformerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var StringDataTransformer
     */
    private $transformer;

    public function __construct()
    {
        parent::__construct();

        $this->transformer = new StringDataTransformer();
    }

    /**
     * Default test case
     */
    public function testBaseStringTransform()
    {
        $this->assertEquals('String', $this->transformer->transform('String'));
        $this->assertEquals(null, $this->transformer->transform(null));
        $this->assertEquals(null, $this->transformer->transform(''));
    }

    /**
     * Transform with trim option
     */
    public function testTrimmedStringTransform()
    {
        $this->transformer->setOptions(['trim' => true]);

        $this->testBaseStringTransform();

        $this->assertEquals('String', $this->transformer->transform(' String '));
        $this->assertEquals('String', $this->transformer->transform("\n\n \n\nString \n \n"));
        $this->assertEquals("String\r \n \rwith some spaces between text", $this->transformer->transform("\t\r\n \n\nString\r \n \rwith some spaces between text\t\n    \n\n\n"));

    }
}