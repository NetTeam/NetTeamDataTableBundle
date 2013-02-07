<?php

namespace NetTeam\Bundle\DataTableBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use NetTeam\Bundle\DataTableBundle\DependencyInjection\DataTableExtension;
use NetTeam\Bundle\DataTableBundle\DependencyInjection\Compiler\DataTablePass;
use NetTeam\Bundle\DataTableBundle\DependencyInjection\Compiler\FilterPass;
use NetTeam\Bundle\DataTableBundle\DependencyInjection\Compiler\ColumnFactoryPass;

class NetTeamDataTableBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new DataTablePass());
        $container->addCompilerPass(new FilterPass());
        $container->addCompilerPass(new ColumnFactoryPass());
        $container->registerExtension(new DataTableExtension());
    }
}
