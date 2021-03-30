<?php

namespace Troytft\DataMapperBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;
use Troytft\DataMapperBundle\Manager;

class DataTransformerCompilerPass implements CompilerPassInterface
{
    public const TAG = 'data_mapper.transformer';

    public function process(ContainerBuilder $container)
    {
        if (!$container->has(Manager::class)) {
            return;
        }

        $definition = $container->findDefinition(Manager::class);
        $taggedServices = $container->findTaggedServiceIds(static::TAG);

        foreach ($taggedServices as $id => $tags) {
            $definition->addMethodCall('addDataTransformer', [new Reference($id)]);
        }
    }
}
