<?php

namespace NetTeam\Bundle\DataTableBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

class DataTablePass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('nt_datatable.factory')) {
            return;
        }

        $datatables = array();
        foreach ($container->findTaggedServiceIds('nt_datatable.table') as $id => $tags) {
            foreach ($tags as $attributes) {
                if (empty($attributes['alias'])) {
                    throw new \InvalidArgumentException(sprintf('The alias is not defined in the "nt_datatable.table" tag for the service "%s"', $id));
                }
                $datatables[$attributes['alias']] = $id;
            }
        }
        $container->getDefinition('nt_datatable.factory')->replaceArgument(1, $datatables);
    }
}
