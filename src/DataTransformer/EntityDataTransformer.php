<?php

namespace Troytft\DataMapperBundle\DataTransformer;

use Doctrine\ORM\EntityManagerInterface;
use Troytft\DataMapperBundle\Exception\ValidationFieldException;
use Troytft\DataMapperBundle\Exception\BaseException;

class EntityDataTransformer extends BaseDataTransformer implements DataTransformerInterface
{
    private $em;
    private $entityName;
    private $fieldName;
    private $nullable = false;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function setOptions(array $options = [])
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

        $fieldType = $this->em->getClassMetadata($this->entityName)->getTypeOfField($this->fieldName);
        if ($fieldType === \Doctrine\DBAL\Types\Type::INTEGER && !is_numeric($value)) {
            throw new ValidationFieldException($this->getPropertyName(), 'Значение должно быть числом');
        } elseif (!is_numeric($value) && !is_string($value)) {
            throw new ValidationFieldException($this->getPropertyName(), 'Значение должно быть строкой или числом');
        }

        $entity = $this->em->getRepository($this->entityName)->findOneBy([$this->fieldName => $value]);
        if (!$entity && !$this->nullable) {
            throw new ValidationFieldException($this->getPropertyName(), 'Сущность с таким значением не найдена');
        }

        return $entity;
    }
}
