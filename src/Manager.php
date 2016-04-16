<?php

namespace Troytft\RequestMapperBundle;

use Common\Exception\FormValidationException;
use Troytft\RequestMapperBundle\Annotation\RequestMapper as RequestMapperAnnotation;
use Troytft\RequestMapperBundle\DataTransformer\BaseDataTransformer;
use Troytft\RequestMapperBundle\Exception\BaseException;
use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class Manager
{
    /**
     * @var AnnotationReader
     */
    private $annotationReader;

    /**
     * @var Request
     */
    private $request;

    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * @var ContainerInterface
     */
    private $container;

    private $model;
    private $activeProperties = [];
    /** @var \ReflectionClass */
    private $reflectedClass;
    private $propertyAssociations = [];
    private $requestData;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->annotationReader = $container->get('annotation_reader');
        $this->request = $container->get('request');
        $this->validator = $container->get('validator');
        var_dump(get_class($this->validator));die();
    }

    /**
     * @return array
     */
    public function getRequestData()
    {
        if ($this->requestData === null) {
            return $this->request->getRealMethod() == 'GET' ? $this->request->query->all() : $this->request->request->all();
        }

        return $this->requestData;
    }

    /**
     * @param array $value
     */
    public function setRequestData($value)
    {
        $this->requestData = (array) $value;

        return $this;
    }

    public function handle($model, $clearMissing = true, $groups = 'Default', $validationGroups = ['Default'])
    {
        $this->model = $model;
        $this->reflectedClass = new \ReflectionClass($this->model);
        $this->activeProperties = [];
        $this->propertyAssociations = [];
        if (is_string($groups)) {
            $groups = [$groups];
        }

        foreach ($this->reflectedClass->getProperties() as $property) {
            /** @var RequestMapperAnnotation $annotation */
            $annotation = $this->annotationReader->getPropertyAnnotation($property, new RequestMapperAnnotation());
            if ($annotation && array_intersect($groups, $annotation->getGroups())) {
                $annotation->setName($annotation->getName() ?: $property->getName());
                $this->propertyAssociations[$property->getName()] = $annotation->getName();
                $this->activeProperties[$annotation->getName()] = $annotation;
            }
        }

        $submittedFields = [];
        foreach ($this->getRequestData() as $propertyName => $value) {
            $submittedFields[] = $propertyName;
            if (!array_key_exists($propertyName, $this->activeProperties)) {
                throw new FormValidationException(['all' => ["Неизвестное поле \"{$propertyName}\""]]);
            }

            $this->setPropertyValue($this->activeProperties[$propertyName], $value);
        }

        if ($clearMissing) {
            foreach ($this->activeProperties as $k => $v) {
                if (!in_array($k, $submittedFields)) {
                    $this->setPropertyValue($this->activeProperties[$k], null);
                }
            }
        }

        $errors = $this->validator->validate($model, $validationGroups);
        if (count($errors)) {
            $errorsAsArray = [];
            foreach ($errors as $error) {
                $key = isset($this->propertyAssociations[$error->getPropertyPath()]) ? $this->propertyAssociations[$error->getPropertyPath()] : 'all';
                $errorsAsArray[$key][] = $error->getMessage();
            }

            throw new FormValidationException($errorsAsArray);
        }

        return $this->model;
    }

    protected function setPropertyValue(RequestMapperAnnotation $propertyAnnotation, $value)
    {
        $methodName = 'set' . ucwords(array_search($propertyAnnotation->getName(), $this->propertyAssociations));
        if (!$this->reflectedClass->hasMethod($methodName)) {
            throw new BaseException("Class don`t have method {$methodName}");
        }

        /** @var BaseDataTransformer $valueTransformer */
        $valueTransformer = $this->container->get('common.helper.request_mapper.data_transformer.' . $propertyAnnotation->getType());
        $valueTransformer->setOptions(array_merge($propertyAnnotation->getOptions(), [
            'model' => $this->model,
            'propertyName' => $propertyAnnotation->getName()
        ]));
        $value = $valueTransformer->transform($value);

        $this->reflectedClass->getMethod($methodName)->invoke($this->model, $value);
    }
}