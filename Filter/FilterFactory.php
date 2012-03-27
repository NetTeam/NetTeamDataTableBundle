<?php

namespace NetTeam\Bundle\DataTableBundle\Filter;

use Symfony\Component\DependencyInjection\ContainerInterface;
use NetTeam\Bundle\DataTableBundle\Filter\Type\FilterTypeInterface;

class FilterFactory
{

    private $container;
    private $types = array();

    public function __construct(ContainerInterface $container, array $types = array())
    {
        $this->container = $container;
        $this->types = $types;
    }

    public function create($name)
    {
        if ($name instanceof FilterTypeInterface) {
            return $name;
        }

        if (!is_string($name)) {
            throw new \InvalidArgumentException(sprintf('Expected argument of type "string", "%s" given', is_object($name) ? get_class($name) : gettype($name)));
        }

        if (!isset($this->types[$name])) {
            throw new \InvalidArgumentException(sprintf('The DataTable Filter "%s" is not defined.', $name));
        }

        $type = $this->container->get($this->types[$name]);
        if (!$type instanceof FilterTypeInterface) {
            throw new \InvalidArgumentException(sprintf('The service "%s" must implement FilterTypeInterface.', $name));
        }

        return $type;
    }

    public function has($name)
    {
        return isset($this->types[$name]);
    }

}
