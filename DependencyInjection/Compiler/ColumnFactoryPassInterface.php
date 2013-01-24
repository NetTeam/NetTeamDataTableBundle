<?php

namespace NetTeam\Bundle\DataTableBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

interface ColumnFactoryPassInterface extends CompilerPassInterface
{
    public function getColumns();
}
