<?php

namespace TestApp\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use TestApp;
use Symfony\Component\Routing\Annotation\Route;

use Troytft\DataMapperBundle\Manager;

class TestController
{
    /**
     * @var Manager
     */
    private $dataMapper;

    /**
     * @var RequestStack
     */
    private $requestStack;

    public function __construct(Manager $dataMapper, RequestStack $requestStack)
    {
        $this->dataMapper = $dataMapper;
        $this->requestStack = $requestStack;
    }

    /**
     * @Route("/test", methods="POST")
     */
    public function testAction(): JsonResponse
    {
        $model = new TestApp\Model\TestModel();
        $this->dataMapper->handle($model, $this->getRequestData());

        return new JsonResponse([
            'dateType' => $model->getDateType(),
            'stringType' => $model->getStringType(),
        ]);
    }

    protected function getRequestData(): array
    {
        $currentRequest = $this->requestStack->getCurrentRequest();
        if (!$currentRequest) {
            throw new \LogicException();
        }

        return $currentRequest->getRealMethod() === 'GET' ? $$currentRequest->query->all() : $currentRequest->request->all();
    }
}
