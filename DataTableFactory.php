<?php

namespace NetTeam\System\DataTableBundle;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;

class DataTableFactory
{
    private $container;
    private $datatables = array();

    public function __construct(ContainerInterface $container, array $datatables = array())
    {
        $this->container = $container;
        $this->datatables = $datatables;
    }

    public function get($name)
    {
        if (!isset($this->datatables[$name])) {
            throw new \InvalidArgumentException(sprintf('The DataTable "%s" is not defined.', $name));
        }

        $datatable = $this->container->get($this->datatables[$name]);
        if ($datatable instanceof ContainerAwareInterface) {
            $datatable->setContainer($this->container);
        }

        $builder = new DataTableBuilder('nt_datatable', $datatable->getSource());
        $builder->setRouteParameters(array('name' => $name));

        $datatable->build($builder);

        return $builder;
    }

    public function has($name)
    {
        return isset($this->datatables[$name]);
    }
}