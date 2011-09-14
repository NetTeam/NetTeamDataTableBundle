<?php

namespace NetTeam\System\DataTableBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use NetTeam\System\DataTableBundle\DependencyInjection\DataTableExtension;

class NetTeamDataTableBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->registerExtension(new DataTableExtension());
    }
}
