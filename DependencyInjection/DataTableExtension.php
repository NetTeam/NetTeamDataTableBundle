<?php

namespace NetTeam\Bundle\DataTableBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

class DataTableExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('datatable.xml');
        $loader->load('filter.xml');
        $loader->load('templating.xml');
        $loader->load('twig.xml');
    }

    public function getAlias()
    {
        return 'nt_datatable';
    }
}
