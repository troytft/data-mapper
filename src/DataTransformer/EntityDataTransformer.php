<?php

namespace Troytft\DataMapperBundle\DataTransformer;

use Troytft\DataMapperBundle\Exception\ValidationFieldException;
use Troytft\DataMapperBundle\Exception\BaseException;
use Doctrine\ORM\EntityManager;

class EntityDataTransformer extends BaseDataTransformer implements DataTransformerInterface
{
    private $em;
    private $entityName;
    private $fieldName;
    private $nullable = false;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function setOptions(array $options)
    {
        parent::setOptions($options);

        if (empty($options['class'])) {
            throw new BaseException('Class name can`t be empty');
        }

        if (isset($options['nullable'])) {
            $this->nullable = (bool) $options['nullable'];
        }

        $this->entityName = $options['class'];
        $this->fieldName = empty($options['field']) ? 'id' : $options['field'];
    }

    public function transform($value)
    {
        if (is_null($value) || $value === "") {
            return null;
        }

        if (!is_string($value) && !is_numeric($value)) {
            throw new ValidationFieldException($this->getPropertyName(), 'Значение должно быть строкой или числом');
        }

        if (!$entity = $this->em->getRepository($this->entityName)->findOneBy([$this->fieldName => $value]) && !$this->nullable) {
            throw new ValidationFieldException($this->getPropertyName(), 'Сущность с таким значением не найдена');
        }

        return $entity;
    }
}
