<?php

namespace NetTeam\Bundle\DataTableBundle\Column\Value;

/**
 *
 * @author zuo
 */
class ColumnValue implements ColumnValueInterface, \ArrayAccess
{
    protected $value;
    protected $route;
    protected $routeParams;
    protected $routeClasses;

    protected $options = array();

    public function __construct($value, $route, $routeParams, $routeClasses, array $options = array())
    {
        $this->value = $value;
        $this->route = $route;
        $this->routeParams = $routeParams;
        $this->routeClasses = $routeClasses;

        $this->options = $options;
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

    public function add($name, $value)
    {
        return $this->options[$name] = $value;
    }

    public function get($name)
    {
        return isset($this->options[$name]) ? $this->options[$name] : null;
    }

    public function offsetGet($name)
    {
        return $this->get($name);
    }

    public function offsetExists($name)
    {
        return isset($this->options[$name]);
    }

    public function offsetSet($name, $value)
    {
        throw new \BadMethodCallException('Not supported');
    }

    public function offsetUnset($name)
    {
        unset($this->options[$name]);
    }
}
?>
