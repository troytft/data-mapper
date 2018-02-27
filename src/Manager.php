<?php

namespace Troytft\DataMapperBundle;

use Doctrine\Common\Annotations\Reader as AnnotationReaderInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
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
     * @param AnnotationReaderInterface $annotationReader
     * @param ValidatorInterface $validator
     */
    public function __construct(AnnotationReaderInterface $annotationReader, ValidatorInterface $validator)
    {
        $this->annotationReader = $annotationReader;
        $this->validator = $validator;
    }

    /**
     * @param object $model
     * @param array $data
     *
     * @return object
     * @throws Exception\UnknownPropertyException
     * @throws Exception\ValidationException
     */
    public function handle($model, $data = [])
    {
        $context = new Helper\Context($this);

        $result = $context
            ->setGroups($this->getGroups())
            ->setValidationGroups($this->getValidationGroups())
            ->setIsClearMissing($this->isIsClearMissing())
            ->setIsValidate($this->isIsValidate())
            ->handle($model, (array) $data);

        $this->shutdown();

        return $result;
    }

    private function shutdown()
    {
        $this->isClearMissing = true;
        $this->groups = ['Default'];
        $this->validationGroups = ['Default'];
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
    public function getDataTransformer($alias)
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
     *
     * @return $this
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
     *
     * @return $this
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
     *
     * @return $this
     */
    public function setGroups($value)
    {
        $this->groups = (array) $value;

        return $this;
    }

    /**
     * @return array
     *
     * @return $this
     */
    public function getValidationGroups()
    {
        return $this->validationGroups;
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

    /**
     * @return AnnotationReaderInterface
     */
    public function getAnnotationReader()
    {
        return $this->annotationReader;
    }

    /**
     * @return ValidatorInterface
     */
    public function getValidator()
    {
        return $this->validator;
    }
}
