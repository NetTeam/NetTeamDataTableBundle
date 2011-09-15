<?php

namespace NetTeam\System\DataTableBundle;

use Symfony\Component\DependencyInjection\ContainerAware;

abstract class DataTableContainerAware extends ContainerAware implements DataTableInterface
{
    protected function get($name)
    {
        return $this->container->get($name);
    }
}