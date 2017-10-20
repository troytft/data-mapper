<?php

namespace Troytft\DataMapperBundle;

use Doctrine\Common\Annotations\Reader as AnnotationReaderInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Troytft\DataMapperBundle\Annotation\DataMapper as DataMapperAnnotation;
use Troytft\DataMapperBundle\DataTransformer\BaseDataTransformer;
use Troytft\DataMapperBundle\DataTransformer\DataTransformerInterface;
use Troytft\DataMapperBundle\Exception;

class Manager
{
    /**
     * @var AnnotationReaderInterface
     */
    private $annotationReader;

    /**
     * @var array
     */
    private $dataTransformers = [];

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
     * @var ValidatorInterface
     */
    private $validator;

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

    public function __construct(AnnotationReaderInterface $annotationReader, ValidatorInterface $validator)
    {
        $this->annotationReader = $annotationReader;
        $this->validator = $validator;
    }

    public function handle($model, $data = [])
    {
        $this->model = $model;
        $this->data = (array) $data;

        $this->analyzeModel();
        $this->settingValues();
        $this->clearMissing();
        $this->validate();
        $this->shutdown();

        return $this->model;
    }
    
    private function analyzeModel()
    {
        $this->reflectedClass = new \ReflectionClass($this->model);

        // tmp hack for doctrine proxy
        $doctrineProxyPrefix = 'Proxies\__CG__\\';
        if (substr($this->reflectedClass->getName(), 0, strlen($doctrineProxyPrefix)) === $doctrineProxyPrefix) {
            $this->reflectedClass = $this->reflectedClass->getParentClass();
        }

        foreach ($this->reflectedClass->getProperties() as $property) {
            /** @var DataMapperAnnotation $annotation */
            $annotation = $this->annotationReader->getPropertyAnnotation($property, new DataMapperAnnotation());
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

        $dataTransformer = $this->getDataTransformer($propertyAnnotation->getType());
        $dataTransformer->setOptions(array_merge($propertyAnnotation->getOptions(), [
            'model' => $this->model,
            BaseDataTransformer::PROPERTY_NAME_OPTION => $propertyAnnotation->getName()
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

        $errors = $this->validator->validate($this->model, null, $this->getValidationGroups());
        if (count($errors)) {
            $errorsAsArray = [];
            foreach ($errors as $error) {
                $key = isset($this->propertyNameToDataKey[$error->getPropertyPath()]) ? $this->propertyNameToDataKey[$error->getPropertyPath()] : 'all';
                $errorsAsArray[$key][] = $error->getMessage();
            }

            throw new Exception\ValidationException($errorsAsArray);
        }
    }

    private function shutdown()
    {
        $this->isClearMissing = true;
        $this->data = [];
        $this->groups = ['Default'];
        $this->validationGroups = ['Default'];
        $this->dataKeyToAnnotation = [];
        $this->propertyNameToDataKey = [];
    }

    public function addDataTransformer(DataTransformerInterface $dataTransformer, $alias)
    {
        $this->dataTransformers[$alias] = $dataTransformer;
    }

    /**
     * @param $alias
     * @return DataTransformerInterface
     * @throws Exception\UnknownDataTransformerException
     */
    private function getDataTransformer($alias)
    {
        if (!array_key_exists($alias, $this->dataTransformers)) {
            throw new Exception\UnknownDataTransformerException($alias);
        }

        return $this->dataTransformers[$alias];
    }

    /**
     * @return boolean
     */
    public function isIsClearMissing()
    {
        return $this->isClearMissing;
    }

    /**
     * @param boolean $value
     */
    public function setIsClearMissing($value)
    {
        $this->isClearMissing = $value;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isIsValidate()
    {
        return $this->isValidate;
    }

    /**
     * @param boolean $value
     */
    public function setIsValidate($value)
    {
        $this->isValidate = $value;

        return $this;
    }

    /**
     * @return array
     */
    public function getGroups()
    {
        return $this->groups;
    }

    /**
     * @param array $value
     */
    public function setGroups($value)
    {
        $this->groups = (array) $value;

        return $this;
    }

    /**
     * @return array
     */
    public function getValidationGroups()
    {
        return $this->validationGroups;
    }

    /**
     * @param array $value
     */
    public function setValidationGroups($value)
    {
        $this->validationGroups = (array) $value;

        return $this;
    }
}
