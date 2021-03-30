<?php

namespace Troytft\DataMapperBundle;

use Doctrine\Common\Annotations\Reader as AnnotationReaderInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Troytft\DataMapperBundle\DataTransformer\DataTransformerInterface;
use Troytft\DataMapperBundle\Exception;
use function array_key_exists;
use function var_dump;

class Manager
{
    /**
     * @var AnnotationReaderInterface
     */
    private $annotationReader;

    /**
     * @var array<string, DataTransformerInterface>
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
     * @var string[]
     */
    private $groups = ['Default'];

    /**
     * @var string[]
     */
    private $validationGroups = ['Default'];

    /**
     * @var ValidatorInterface
     */
    private $validator;

    public function __construct(AnnotationReaderInterface $annotationReader, ValidatorInterface $validator)
    {
        $this->annotationReader = $annotationReader;
        $this->validator = $validator;
    }

    /**
     * @param object $model
     *
     * @return object
     */
    public function handle($model, array $data = [], bool $shouldShutdown = true)
    {
        $context = new Helper\Context($this);

        $result = $context
            ->setGroups($this->getGroups())
            ->setValidationGroups($this->getValidationGroups())
            ->setIsClearMissing($this->isIsClearMissing())
            ->setIsValidate($this->isIsValidate())
            ->handle($model, (array) $data);

        if ($shouldShutdown) {
            $this->shutdown();
        }

        return $result;
    }

    private function shutdown(): void
    {
        $this->isClearMissing = true;
        $this->groups = ['Default'];
        $this->validationGroups = ['Default'];
    }

    public function addDataTransformer(DataTransformerInterface $dataTransformer)
    {
        $this->dataTransformers[$dataTransformer::getAlias()] = $dataTransformer;
    }

    public function getDataTransformer(string $alias): DataTransformerInterface
    {
        if (!$this->hasDataTransformer($alias)) {
            throw new Exception\UnknownDataTransformerException($alias);
        }

        return $this->dataTransformers[$alias];
    }

    public function hasDataTransformer(string $alias): bool
    {
        return array_key_exists($alias, $this->dataTransformers);
    }

    public function isIsClearMissing(): bool
    {
        return $this->isClearMissing;
    }

    /**
     * @return $this
     */
    public function setIsClearMissing(bool $value)
    {
        $this->isClearMissing = $value;

        return $this;
    }

    public function isIsValidate(): bool
    {
        return $this->isValidate;
    }

    /**
     * @return $this
     */
    public function setIsValidate(bool $value)
    {
        $this->isValidate = $value;

        return $this;
    }

    /**
     * @return string[]
     */
    public function getGroups(): array
    {
        return $this->groups;
    }

    /**
     * @param string[] $groups
     *
     * @return $this
     */
    public function setGroups(array $groups)
    {
        $this->groups = $groups;

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
     * @param string[] $value
     *
     * @return $this
     */
    public function setValidationGroups(array $value)
    {
        $this->validationGroups = $value;

        return $this;
    }

    public function getAnnotationReader(): AnnotationReaderInterface
    {
        return $this->annotationReader;
    }

    public function getValidator(): ValidatorInterface
    {
        return $this->validator;
    }
}
