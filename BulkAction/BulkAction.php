<?php

namespace NetTeam\System\DataTableBundle\BulkAction;

class BulkAction
{

    protected $description;
    protected $route;
    protected $params = array();

    public function __construct($description, $route, array $params = array())
    {
        $this->route = $route;
        $this->params = $params;
        $this->description = $description;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getRoute()
    {
        return $this->route;
    }

    public function getParams()
    {
        return $this->params;
    }

    public function setRoute($route)
    {
        $this->route = $route;
    }

    public function setParams($params)
    {
        $this->params = $params;
    }
}
