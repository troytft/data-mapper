<?php

namespace Troytft\DataMapperBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;

class DataTransformerCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->has('data_mapper.manager')) {
            return;
        }

        $definition = $container->findDefinition('data_mapper.manager');
        $taggedServices = $container->findTaggedServiceIds('data_mapper.transformer');
        
        foreach ($taggedServices as $id => $tags) {
            foreach ($tags as $attributes) {
                $definition->addMethodCall('addDataTransformer', [new Reference($id), $attributes["alias"]]);
            }
        }
    }
}