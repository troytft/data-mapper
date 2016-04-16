<?php

namespace Troytft\RequestMapperBundle\DataTransformer;

use Common\Exception\FormValidationFieldException;
use Troytft\RequestMapperBundle\Exception\BaseException;
use Doctrine\ORM\EntityManager;

class ArrayOfEntityDataTransformer extends BaseDataTransformer implements DataTransformerInterface
{
    private $em;
    private $entityName;
    private $fieldName;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function setOptions($options)
    {
        if (empty($options['class'])) {
            throw new BaseException('Class name can`t be empty');
        }

        $this->entityName = $options['class'];
        $this->fieldName = empty($options['field']) ? 'id' : $options['field'];
        parent::setOptions($options);
    }

    public function transform($value)
    {
        if (is_null($value)) {
            $value = [];
        }

        if (!is_array($value)) {
            throw new FormValidationFieldException($this->getPropertyName(), 'Значение должно быть массивом');
        }

        foreach ($value as $v) {
            if (!is_numeric($v) && !is_string($v)) {
                throw new FormValidationFieldException($this->getPropertyName(), 'Значения массива должны быть числами или строками');
            }

            if (!$v) {
                throw new FormValidationFieldException($this->getPropertyName(), 'Значения массива не должны быть пустыми');
            }
        }

        $results = $this->em->getRepository($this->entityName)->findBy([$this->fieldName => $value]);
        if (count($results) !== count($value)) {
            throw new FormValidationFieldException($this->getPropertyName(), 'Не найдена сущность по одному из значений массива');
        }

        return $results;
    }
}