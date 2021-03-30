<?php

use Troytft\DataMapperBundle\DataTransformer\StringDataTransformer;
use PHPUnit\Framework\TestCase;

class StringDataTransformerTest extends TestCase
{
    /**
     * Default test case
     */
    public function testBaseStringTransform()
    {
        $transformer = new StringDataTransformer();
        
        $this->assertEquals('String', $transformer->transform('String'));
        $this->assertEquals(null, $transformer->transform(null));
        $this->assertEquals(null, $transformer->transform(''));
    }

    /**
     * Transform with trim option
     */
    public function testTrimmedStringTransform()
    {
        $transformer = new StringDataTransformer();
        $transformer->setOptions(['trim' => true]);

        $this->assertEquals('String', $transformer->transform(' String '));
        $this->assertEquals('String', $transformer->transform("\n\n \n\nString \n \n"));
        $this->assertEquals("String\r \n \rwith some spaces between text", $transformer->transform("\t\r\n \n\nString\r \n \rwith some spaces between text\t\n    \n\n\n"));

    }
}
