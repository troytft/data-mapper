<?php

namespace Troytft\DataMapperBundle\DataTransformer;

use Doctrine\ORM\EntityManager;
use Troytft\DataMapperBundle\Exception\BaseException;
use Troytft\DataMapperBundle\Exception\ValidationFieldException;

class EntityDataTransformer extends BaseDataTransformer implements DataTransformerInterface
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
            return null;
        }

        if (!is_string($value) && !is_numeric($value)) {
            throw new ValidationFieldException($this->getPropertyName(), 'Значение должно быть строкой или числом');
        }

        if (!$entity = $this->em->getRepository($this->entityName)->findOneBy([$this->fieldName => $value])) {
            throw new ValidationFieldException($this->getPropertyName(), 'Сущность с таким значением не найдена');
        }

        return $entity;
    }
}