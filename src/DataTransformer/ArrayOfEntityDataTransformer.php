<?php

namespace Troytft\DataMapperBundle\DataTransformer;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\EntityManagerInterface;
use function array_search;
use function ksort;
use function method_exists;
use Troytft\DataMapperBundle\Exception\ValidationFieldException;
use Troytft\DataMapperBundle\Exception\BaseException;
use function ucfirst;

class ArrayOfEntityDataTransformer extends BaseArrayDataTransformer implements DataTransformerInterface
{
    private $em;
    private $entityName;
    private $fieldName;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function setOptions(array $options = []): void
    {
        parent::setOptions($options);

        if (empty($options['class'])) {
            throw new BaseException('Class name can`t be empty');
        }

        $this->entityName = $options['class'];
        $this->fieldName = empty($options['field']) ? 'id' : $options['field'];
    }

    public function transform($value)
    {
        if ($value === null) {
            if ($this->isNullable) {
                return null;
            }
            if ($this->isForceArray) {
                $value = [];
            }
        }

        if (!is_array($value)) {
            throw new ValidationFieldException($this->getPropertyName(), 'Значение должно быть массивом');
        }

        $fieldType = $this->em->getClassMetadata($this->entityName)->getTypeOfField($this->fieldName);
        foreach ($value as $v) {
            if ($fieldType === Types::INTEGER && !is_numeric($v)) {
                throw new ValidationFieldException($this->getPropertyName(), 'Значения массива должны быть числами');
            } elseif (!is_numeric($v) && !is_string($v)) {
                throw new ValidationFieldException($this->getPropertyName(), 'Значения массива должны быть числами или строками');
            }

            if (!$v) {
                throw new ValidationFieldException($this->getPropertyName(), 'Значения массива не должны быть пустыми');
            }
        }

        $results = $this->em->getRepository($this->entityName)->findBy([$this->fieldName => $value]);
        if (count($results) !== count($value)) {
            throw new ValidationFieldException($this->getPropertyName(), 'Не найдена сущность по одному из значений массива');
        }

        $sortedResults = [];
        $getterName = 'get' . ucfirst($this->fieldName);

        foreach ($results as $object) {
            if (!method_exists($object, $getterName)) {
                throw new \InvalidArgumentException();
            }

            $key = array_search($object->{$getterName}(), $value);
            if ($key === false) {
                throw new \InvalidArgumentException();
            }

            $sortedResults[$key] = $object;
        }

        unset($results);
        ksort($sortedResults);

        return $sortedResults;
    }

    public static function getAlias(): string
    {
        return 'array_of_entity';
    }
}
