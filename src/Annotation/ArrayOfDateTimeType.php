<?php

namespace Troytft\DataMapperBundle\Annotation;

/**
 * @Annotation
 */
class ArrayOfDateTimeType extends DataMapper
{
    /**
     * @var bool
     */
    private $nullable = false;

    /**
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        parent::__construct($options);

        if (isset($options['nullable'])) {
            $this->nullable = $options['nullable'];
        }
    }

    /**
     * @return string
     */
    public function getType()
    {
        return 'array_of_datetime';
    }

    /**
     * @inheritdoc
     */
    public function getOptions()
    {
        return array_merge(parent::getOptions(), [
            'setLocalTimeZone' => true,
            'nullable' => $this->nullable,
        ]);
    }
}
