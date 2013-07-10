<?php

namespace NetTeam\Bundle\DataTableBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use NetTeam\Bundle\DataTableBundle\DependencyInjection\DataTableExtension;
use NetTeam\Bundle\DataTableBundle\DependencyInjection\Compiler\ColumnFactoryPass;
use NetTeam\Bundle\DataTableBundle\DependencyInjection\Compiler\DataTablePass;
use NetTeam\Bundle\DataTableBundle\DependencyInjection\Compiler\ExportPass;
use NetTeam\Bundle\DataTableBundle\DependencyInjection\Compiler\FilterPass;

class NetTeamDataTableBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new DataTablePass());
        $container->addCompilerPass(new FilterPass());
        $container->addCompilerPass(new ColumnFactoryPass());
        $container->addCompilerPass(new ExportPass());
        $container->registerExtension(new DataTableExtension());
    }
}
