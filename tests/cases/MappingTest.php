<?php

use Symfony\Component\HttpFoundation\Request;
use Tests\BaseTestCase;
use Troytft\DataMapperBundle\DataTransformer\EntityDataTransformer;
use Troytft\DataMapperBundle\Manager;

class MappingTest extends BaseTestCase
{
    public function testSuccess()
    {
        $jsonBody = '{"dateType": "2019-03-02"}';
        $request = Request::create('http://localhost/test', 'POST', [], [], [], [], $jsonBody);
        $response = $this->getKernel()->handle($request);

        $this->assertSame(200, $response->getStatusCode());
    }
}
