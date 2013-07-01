<?php

namespace NetTeam\Bundle\DataTableBundle\DataTable;

use Symfony\Component\DependencyInjection\ContainerAware;

abstract class DataTable extends ContainerAware implements DataTableInterface
{
    protected $options = array();

    public function getRequiredOptions()
    {
        return array();
    }

    public function setOptions(array $options)
    {
        $this->options = $options;
    }

    public function getOption($name)
    {
        return isset($this->options[$name]) ? $this->options[$name] : null;
    }

    public function getOptions()
    {
        return $this->options;
    }

    protected function has($id)
    {
        return $this->container->has($id);
    }

    protected function get($id)
    {
        return $this->container->get($id);
    }
}
