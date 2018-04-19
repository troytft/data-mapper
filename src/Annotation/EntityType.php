<?php

namespace Troytft\DataMapperBundle\Annotation;

use Doctrine\Common\Annotations\Annotation;

/**
 * @Annotation
 */
class EntityType extends DataMapper
{
    /**
     * @var string|null
     */
    private $class;

    /**
     * @var strin|gnull
     */
    private $field = 'id';

    /**
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        parent::__construct($options);

        if (isset($options['class'])) {
            $this->class = $options['class'];
        }

        if (isset($options['field'])) {
            $this->field = $options['field'];
        }
    }

    /**
     * @return string
     */
    public function getType()
    {
        return 'entity';
    }

    /**
     * @inheritdoc
     */
    public function getOptions()
    {
        return array_merge(parent::getOptions(), [
            'class' => $this->class,
            'field' => $this->field
        ]);
    }
}
