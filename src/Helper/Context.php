<?php

namespace Troytft\DataMapperBundle\Helper;

use Troytft\DataMapperBundle\Annotation\DataMapper as DataMapperAnnotation;
use Troytft\DataMapperBundle\DataTransformer\BaseDataTransformer;
use Troytft\DataMapperBundle\Exception;
use Troytft\DataMapperBundle\Manager;

class Context
{
    /**
     * @var Manager;
     */
    private $manager;

    /**
     * @var bool
     */
    private $isClearMissing = true;

    /**
     * @var bool
     */
    private $isValidate = true;

    /**
     * @var array
     */
    private $groups = ['Default'];

    /**
     * @var array
     */
    private $validationGroups = ['Default'];

    /**
     * @var object
     */
    private $model;

    /**
     * @var array
     */
    private $data = [];

    /**
     * @var array
     */
    private $dataKeyToAnnotation = [];

    /**
     * @var array
     */
    private $propertyNameToDataKey = [];

    /**
     * @var \ReflectionClass
     */
    private $reflectedClass;

    /**
     * @param Manager $manager
     */
    public function __construct(Manager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @return Manager
     */
    private function getManager()
    {
        return $this->manager;
    }

    /**
     * @param object $model
     * @param array $data
     *
     * @return object
     * @throws Exception\UnknownPropertyException
     * @throws Exception\ValidationException
     */
    public function handle($model, array $data)
    {
        $this->model = $model;
        $this->data = $data;

        $this->analyzeModel();
        $this->settingValues();
        $this->clearMissing();
        $this->validate();

        return $this->model;
    }

    private function analyzeModel()
    {
        $this->reflectedClass = new \ReflectionClass($this->model);

        // tmp hack for doctrine proxy
        $doctrineProxyPrefix = 'Proxies\__CG__\\';
        if (substr($this->reflectedClass->getName(), 0, strlen($doctrineProxyPrefix)) === $doctrineProxyPrefix) {
            $properties = $this->reflectedClass->getParentClass()->getProperties();
        } else {
            $properties = $this->reflectedClass->getProperties();
        }

        foreach ($properties as $property) {
            /** @var DataMapperAnnotation $annotation */
            $annotation = $this->getManager()->getAnnotationReader()->getPropertyAnnotation($property, new DataMapperAnnotation());
            if ($annotation && array_intersect($this->groups, $annotation->getGroups())) {
                $annotation->setName($annotation->getName() ?: $property->getName());

                $this->propertyNameToDataKey[$property->getName()] = $annotation->getName();
                $this->dataKeyToAnnotation[$annotation->getName()] = $annotation;
            }
        }
    }

    private function settingValues()
    {
        foreach ($this->data as $propertyName => $value) {
            if (!array_key_exists($propertyName, $this->dataKeyToAnnotation)) {
                throw new Exception\UnknownPropertyException($propertyName);
            }

            $this->setPropertyValue($this->dataKeyToAnnotation[$propertyName], $value);
        }
    }

    private function setPropertyValue(DataMapperAnnotation $propertyAnnotation, $value)
    {
        $propertyName = array_search($propertyAnnotation->getName(), $this->propertyNameToDataKey);
        $methodName = 'set' . ucwords($propertyName);

        if (!$this->reflectedClass->hasMethod($methodName)) {
            throw new Exception\UnknownPropertySetterException($propertyName);
        }

        $dataTransformer = $this->getManager()->getDataTransformer($propertyAnnotation->getType());
        $dataTransformer->setOptions(array_merge($propertyAnnotation->getOptions(), [
            BaseDataTransformer::MODEL_OPTION => $this->model,
            BaseDataTransformer::MODEL_REFLECTION_OPTION => $this->reflectedClass,
            BaseDataTransformer::PROPERTY_NAME_OPTION => $propertyAnnotation->getName(),
        ]));
        $value = $dataTransformer->transform($value);

        $this->reflectedClass->getMethod($methodName)->invoke($this->model, $value);
    }

    private function clearMissing()
    {
        if (!$this->isClearMissing) {
            return;
        }

        $dataKeys = array_keys($this->data);
        foreach ($this->dataKeyToAnnotation as $k => $v) {
            if (!in_array($k, $dataKeys)) {
                $this->setPropertyValue($this->dataKeyToAnnotation[$k], null);
            }
        }
    }

    private function validate()
    {
        if (!$this->isValidate) {
            return;
        }

        $errors = $this->getManager()->getValidator()->validate($this->model, null, $this->validationGroups);
        if (count($errors)) {
            $errorsAsArray = [];
            foreach ($errors as $error) {
                $key = isset($this->propertyNameToDataKey[$error->getPropertyPath()]) ? $this->propertyNameToDataKey[$error->getPropertyPath()] : 'all';
                $errorsAsArray[$key][] = $error->getMessage();
            }

            throw new Exception\ValidationException($errorsAsArray);
        }
    }

    /**
     * @param boolean $value
     *
     * @return $this
     */
    public function setIsClearMissing($value)
    {
        $this->isClearMissing = $value;

        return $this;
    }

    /**
     * @param boolean $value
     *
     * @return $this
     */
    public function setIsValidate($value)
    {
        $this->isValidate = $value;

        return $this;
    }

    /**
     * @param array $value
     *
     * @return $this
     */
    public function setGroups($value)
    {
        $this->groups = (array) $value;

        return $this;
    }

    /**
     * @param array $value
     *
     * @return $this
     */
    public function setValidationGroups($value)
    {
        $this->validationGroups = (array) $value;

        return $this;
    }
}
