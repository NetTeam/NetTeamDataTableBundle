<?php

namespace NetTeam\Bundle\DataTableBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

abstract class AbstractColumnFactoryPass implements CompilerPassInterface
{
    abstract protected function getColumns();

    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('nt_datatable.factory.column')) {
            return;
        }

        $columns = $this->getColumns();

        foreach ($columns as $name => $class) {
            $container->getDefinition('nt_datatable.factory.column')->addMethodCall('addColumnType', array($name, $class));
        }
    }
}
