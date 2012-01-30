<?php

namespace NetTeam\System\DataTableBundle\Filter\Value;

/**
 *
 * @author zuo
 */
class FilterValue implements \ArrayAccess, \IteratorAggregate, \Countable
{
    protected $values = array();

    public function __construct(array $values = array())
    {
        $this->values = $values;
    }
    
    public function add($name, $value)
    {
        return $this->values[$name] = $value;
    }

    public function get($name)
    {
        return isset($this->values[$name]) ? $this->values[$name] : null;
    }

    public function offsetGet($name)
    {
        return $this->get($name);
    }

    public function offsetExists($name)
    {
        return isset($this->values[$name]);
    }

    public function offsetSet($name, $value)
    {
        return $this->add($name, $value);
    }

    public function offsetUnset($name)
    {
        unset($this->values[$name]);
    }
    
    public function getIterator()
    {
        return new \ArrayIterator($this->values);
    }
    
    public function count()
    {
        return count($this->values);
    }
    
    public function __get($name)
    {
        return $this->get($name);
    }
    
    public function __set($name, $value)
    {
        return $this->add($name, $value);
    }
}
?>
