<?php

namespace NetTeam\Bundle\DataTableBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Compiler Pass do funkcjonalności eksportu, ładuje zatagowane serwisy eksportu do fabryki
 *
 * @author Paweł Macyszyn <pawel.macyszyn@netteam.pl>s
 */
class ExportPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('nt_datatable.export.container')) {
            return;
        }

        $exports = array();

        foreach ($container->findTaggedServiceIds('nt_datatable.export') as $id => $tags) {
            foreach ($tags as $attributes) {
                if (empty($attributes['alias'])) {
                    throw new \InvalidArgumentException(sprintf('The alias is not defined in the "nt_datatable.export" tag for the service "%s"', $id));
                }

                $exports[$attributes['alias']] = $id;
            }
        }

        $container->getDefinition('nt_datatable.export.container')->replaceArgument(1, $exports);
    }
}
