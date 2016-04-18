<?php

namespace Troytft\DataMapperBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Troytft\DataMapperBundle\DependencyInjection\Compiler\DataTransformerCompilerPass;

class DataMapperBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        
        $container->addCompilerPass(new DataTransformerCompilerPass());
    }
}