<?php

namespace NetTeam\System\DataTableBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use NetTeam\System\DataTableBundle\DependencyInjection\DataTableExtension;
use NetTeam\System\DataTableBundle\DependencyInjection\Compiler\DataTablePass;
use NetTeam\System\DataTableBundle\DependencyInjection\Compiler\FilterPass;

class NetTeamDataTableBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new DataTablePass());
        $container->addCompilerPass(new FilterPass());
        $container->registerExtension(new DataTableExtension());
    }
}
