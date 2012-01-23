<?php

namespace NetTeam\System\DataTableBundle\Column\Value;

/**
 *
 * @author zuo
 */
class ColumnValue implements ColumnValueInterface
{
    protected $value;
    protected $route;
    protected $routeParams;
    protected $routeClasses;

    public function __construct($value, $route, $routeParams, $routeClasses)
    {
        $this->value = $value;
        $this->route = $route;
        $this->routeParams = $routeParams;
        $this->routeClasses = $routeClasses;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function getRoute()
    {
        return $this->route;
    }

    public function getRouteParams()
    {
        return $this->routeParams;
    }

    public function getRouteClasses()
    {
        return $this->routeClasses;
    }

}
?>
