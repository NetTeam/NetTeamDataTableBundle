<?php

namespace NetTeam\System\DataTableBundle;

use Symfony\Component\DependencyInjection\ContainerAware;

abstract class DataTable extends ContainerAware implements DataTableInterface
{
    protected function has($id)
    {
        return $this->container->has($id);
    }

    protected function get($id)
    {
        return $this->container->get($id);
    }
}