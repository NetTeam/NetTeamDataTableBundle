<?php

namespace NetTeam\Bundle\DataTableBundle\Filter;

use NetTeam\Bundle\DataTableBundle\Filter\Type\FilterTypeInterface;

class Filter
{

    protected $key;
    protected $container;
    protected $type;

    public function __construct($key, FilterContainer $container, FilterTypeInterface $type, $callback)
    {
        $this->key = $key;
        $this->container = $container;
        $this->type = $type;
        $this->callback = $callback;

        $this->type->buildForm($container->getBuilder($this->key));
    }

    public function getType()
    {
        return $this->type;
    }

    public function getCallback()
    {
        return $this->callback;
    }

    public function getForm()
    {
        return $this->container->getFormView($this->key);
    }

    public function getTemplate()
    {
        return $this->type->getTemplate();
    }

    public function apply()
    {
        $this->type->apply($this->callback, $this->container->getModel($this->key));
    }

}
