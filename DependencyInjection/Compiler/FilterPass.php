<?php

namespace NetTeam\Bundle\DataTableBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

class FilterPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('nt_datatable.filter_factory')) {
            return;
        }

        $filters = array();
        foreach ($container->findTaggedServiceIds('nt_datatable.filter') as $id => $tags) {
            foreach ($tags as $attributes) {
                if (empty($attributes['alias'])) {
                    throw new \InvalidArgumentException(sprintf('The alias is not defined in the "nt_datatable.filter" tag for the service "%s"', $id));
                }
                $filters[$attributes['alias']] = $id;
            }
        }
        $container->getDefinition('nt_datatable.filter_factory')->replaceArgument(1, $filters);
    }
}
