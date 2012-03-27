<?php

namespace NetTeam\Bundle\DataTableBundle\BulkAction;

use NetTeam\Bundle\DataTableBundle\Column\ValueGetter\ClosureValueGetter;
use NetTeam\Bundle\DataTableBundle\Column\ValueGetter\PropertyValueGetter;

class Column
{
    protected $width = null;
    protected $getter;
    protected $class = array();
    protected $headerClass = array();
    protected $headerTemplate = 'column_header';
    protected $template = 'column';

    public function __construct($getter = 'id')
    {
        $this->setGetter($getter);
    }

    public function setGetter($getter)
    {
        if (is_string($getter)) {
            $this->getter = new PropertyValueGetter($getter);
            return $this;
        }

        if ($getter instanceof \Closure) {
            $this->getter = new ClosureValueGetter($getter);
            return $this;
        }

        throw new \InvalidArgumentException('Incorrect definition of column key');
    }

    public function setWidth($width)
    {
        $this->width = $width;
        return $this;
    }

    public function getWidth()
    {
        return $this->width;
    }

    public function hasWidth()
    {
        return $this->width !== null;
    }

    public function addClass($class)
    {
        if (!in_array($class, $this->class)) {
            $this->class[] = $class;
        }
        return $this;
    }

    public function getClass()
    {
        return implode(' ', $this->class);
    }

    public function addHeaderClass($class)
    {
        if (!in_array($class, $this->headerClass)) {
            $this->headerClass[] = $class;
        }
        return $this;
    }

    public function getHeaderClass()
    {
        return implode(' ', $this->headerClass);
    }

    public function getHeaderTemplate()
    {
        return $this->headerTemplate;
    }

    public function setHeaderTemplate($headerTemplate)
    {
        $this->headerTemplate = $headerTemplate;
        return $this;
    }

    public function getTemplate()
    {
        return $this->template;
    }

    public function setTemplate($template)
    {
        $this->template = $template;
        return $this;
    }

    public function getValue($objectOrArray)
    {
        return $this->getter->get($objectOrArray);
    }

}