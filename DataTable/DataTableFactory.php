<?php

namespace NetTeam\System\DataTableBundle\DataTable;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use NetTeam\System\DataTableBundle\SimpleSource\SimpleSourceInterface;
use NetTeam\System\DataTableBundle\Source\SourceInterface;
use NetTeam\System\DataTableBundle\Source\SimpleSourceAdapter;

class DataTableFactory
{

    private $container;
    private $datatables = array();

    public function __construct(ContainerInterface $container, array $datatables = array())
    {
        $this->container = $container;
        $this->datatables = $datatables;
    }

    public function create($name, array $options)
    {
        $datatable = $this->getDatatable($name, $options);
        if ($datatable instanceof ContainerAwareInterface) {
            $datatable->setContainer($this->container);
        }

        $source = $datatable->getSource();

        $builder = new DataTableBuilder('nt_datatable', $this->prepareSource($source), array('name' => $name));
        $builder->setRouteParameters($options);

        $filterContainer = $this->container->get('nt_datatable.filter_container');
        $filterContainer->setName($name);
        $builder->setFilterContainer($filterContainer);

        $datatable->build($builder);
        $builder->buildExports();

        $this->checkSource($name, $builder, $source);

        return $builder;
    }

    public function has($name)
    {
        return isset($this->datatables[$name]);
    }

    private function getDatatable($name, array $options)
    {
        if (!is_string($name)) {
            throw new \InvalidArgumentException(sprintf('Expected argument of type "string", "%s" given.', is_object($name) ? get_class($name) : gettype($name)));
        }

        if (!isset($this->datatables[$name])) {
            throw new \InvalidArgumentException(sprintf('The DataTable "%s" is not defined.', $name));
        }

        $datatable = $this->container->get($this->datatables[$name]);
        if (!$datatable instanceof DataTableInterface) {
            throw new \InvalidArgumentException(sprintf('The service "%s" must implement DataTableInterface.', $name));
        }

        foreach ($datatable->getRequiredOptions() as $option) {
            if (!isset($options[$option])) {
                throw new \InvalidArgumentException(sprintf('The option "%s" in the DataTable "%s" is required.', $option, $name));
            }
        }

        $datatable->setOptions($options);

        return $datatable;
    }

    private function prepareSource($source)
    {
        if ($source instanceof SimpleSourceInterface) {
            return new SimpleSourceAdapter($source);
        }

        return $source;
    }

    private function checkSource($name, DataTableBuilder $builder, $source)
    {
        if (!$builder->isSimple() && !$source instanceof SourceInterface) {
            throw new \InvalidArgumentException(sprintf('Method "getSource()" in the DateTable "%s" must return SourceInterface implementation.', $name));
        }

        if ($builder->isSimple() && !$source instanceof SimpleSourceInterface) {
            throw new \InvalidArgumentException(sprintf('Method "getSource()" in the DateTable "%s" must return SimpleSourceInterface implementation.', $name));
        }
    }

}