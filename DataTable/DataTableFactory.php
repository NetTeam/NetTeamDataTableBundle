<?php

namespace NetTeam\Bundle\DataTableBundle\DataTable;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use NetTeam\Bundle\DataTableBundle\SimpleSource\SimpleSourceInterface;
use NetTeam\Bundle\DataTableBundle\Source\SourceInterface;
use NetTeam\Bundle\DataTableBundle\Source\SimpleSourceAdapter;
use NetTeam\Bundle\DataTableBundle\Factory\ColumnFactory;

class DataTableFactory
{

    private $container;
    private $datatables = array();
    private $columnFactory;

    /**
     * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
     * @param \NetTeam\Bundle\DataTableBundle\Factory\ColumnFactory     $columnFactory
     * @param array                                                     $datatables
     */
    public function __construct(ContainerInterface $container, ColumnFactory $columnFactory, array $datatables = array())
    {
        $this->container = $container;
        $this->datatables = $datatables;
        $this->columnFactory = $columnFactory;
    }

    /**
     * @param  string                                                     $name
     * @param  array                                                      $options
     * @return \NetTeam\Bundle\DataTableBundle\DataTable\DataTableBuilder
     */
    public function create($name, array $options)
    {
        $datatable = $this->getDatatable($name, $options);
        if ($datatable instanceof ContainerAwareInterface) {
            $datatable->setContainer($this->container);
        }

        $source = $datatable->getSource();

        $builder = new DataTableBuilder('nt_datatable', $this->prepareSource($source), $name);
        $builder->setContext($this->createContext($datatable));
        $builder->setRouteParameters($options);
        $builder->setColumnFactory($this->columnFactory);

        $filterContainer = $this->container->get('nt_datatable.filter_container');
        $filterContainer->setName($name);
        $builder->setFilterContainer($filterContainer);

        $datatable->build($builder);

        $this->checkSource($name, $builder, $source);

        return $builder;
    }

    /**
     * @param  string  $name
     * @return boolean
     */
    public function has($name)
    {
        return isset($this->datatables[$name]);
    }

    /**
     * @param  string                                                       $name
     * @param  array                                                        $options
     * @return \NetTeam\Bundle\DataTableBundle\DataTable\DataTableInterface
     * @throws \InvalidArgumentException
     */
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

    /**
     *
     * @param  \NetTeam\Bundle\DataTableBundle\SimpleSource\SimpleSourceInterface                                                        $source
     * @return \NetTeam\Bundle\DataTableBundle\Source\SourceInterface|\NetTeam\Bundle\DataTableBundle\SimpleSource\SimpleSourceInterface
     */
    private function prepareSource($source)
    {
        if ($source instanceof SimpleSourceInterface) {
            return new SimpleSourceAdapter($source);
        }

        return $source;
    }

    /**
     * @param  string                                                                                                                    $name
     * @param  \NetTeam\Bundle\DataTableBundle\DataTable\DataTableBuilder                                                                $builder
     * @param  \NetTeam\Bundle\DataTableBundle\Source\SourceInterface|\NetTeam\Bundle\DataTableBundle\SimpleSource\SimpleSourceInterface $source
     * @throws \InvalidArgumentException
     */
    private function checkSource($name, DataTableBuilder $builder, $source)
    {
        if (!$builder->isSimple() && !$source instanceof SourceInterface) {
            throw new \InvalidArgumentException(sprintf('Method "getSource()" in the DateTable "%s" must return SourceInterface implementation.', $name));
        }

        if ($builder->isSimple() && !$source instanceof SimpleSourceInterface) {
            throw new \InvalidArgumentException(sprintf('Method "getSource()" in the DateTable "%s" must return SimpleSourceInterface implementation.', $name));
        }
    }
    /**
     *
     * @param  \NetTeam\Bundle\DataTableBundle\DataTable\DataTableInterface $dataTable
     * @return array
     * @throws \Exception
     */
    private function createContext(DataTableInterface $dataTable)
    {
        $requiredOptions = $dataTable->getRequiredOptions();
        $options = array();
        foreach ($requiredOptions as $requiredOption) {
            $options[$requiredOption] = $dataTable->getOption($requiredOption);
        }

        return $options;
    }

}
