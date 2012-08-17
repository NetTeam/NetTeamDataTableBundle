<?php

namespace NetTeam\Bundle\DataTableBundle\Column;

/**
 * Klasa do dekorowania ColumnInterface
 *
 * @author Krzysztof Menżyk <krzysztof.menzyk@carrywater.pl>
 */
abstract class ColumnDecorator implements ColumnInterface
{

    protected $column;

    public static function create($name, $getters, array $parameters)
    {
        return new static($name, $getters, $parameters);
    }

    public function __construct($name, $getters, array $parameters)
    {
        $columnClass = $this->getColumn();
        $column = $columnClass::create($name, $getters, $parameters);

        $this->column = $column;
    }

    public function getValue($objectOrArray)
    {
        return $this->column->getValue($objectOrArray);
    }

    public function getTemplate()
    {
        return $this->column->getTemplate();
    }

    public function getParameters()
    {
        return $this->column->getParameters();
    }

    public function addGetter($key)
    {
        $this->column->addGetter($key);

        return $this;
    }

    public function getter($key)
    {
        return $this->addGetter($key);
    }

    public function setPriority($priority)
    {
        $this->column->setPriority($priority);

        return $this;
    }

    public function priority($priority)
    {
        return $this->setPriority($priority);
    }

    public function getPriority()
    {
        return $this->column->getPriority();
    }

    public function addClass($class)
    {
        $this->column->addClass($class);

        return $this;
    }

    public function class_($class)
    {
        return $this->addClass($class);
    }

    public function getClass()
    {
        return $this->column->getClass();
    }

    public function setWidth($width)
    {
        $this->column->setWidth($width);

        return $this;
    }

    public function width($width)
    {
        return $this->setWidth($width);
    }

    public function getWidth()
    {
        return $this->column->getWidth();
    }

    public function hasWidth()
    {
        return $this->column->hasWidth();
    }

    public function setSortable($columns)
    {
        $this->column->setSortable($columns);

        return $this;
    }

    public function sortable($keys, $order = null)
    {
        $this->column->sortable($keys, $order);

        return $this;
    }

    public function addSortable($key)
    {
        $this->column->addSortable($key);

        return $this;
    }

    public function isSortable()
    {
        return $this->column->isSortable();
    }

    public function sortByDefault($order = 'ASC')
    {
        return $this->column->sortByDefault();
    }

    public function isSortedByDefault()
    {
        return $this->column->isSortedByDefault();
    }

    public function getDefaultSorting()
    {
        return $this->column->getDefaultSorting();
    }

    public function getSortableKeys()
    {
        return $this->column->getSortableKeys();
    }

    public function getCaption()
    {
        return $this->column->getCaption();
    }

    public function getTranslate()
    {
        return $this->column->getTranslate();
    }

    public function setTranslate($translate)
    {
        $this->column->setTranslate($translate);

        return $this;
    }

    public function setRoute($route, array $params, $routeClass = null)
    {
        return $this->column->setRoute($route, $params, $routeClass);
    }

    public function route($route, array $params, $routeClass = null)
    {
        return $this->column->route($route, $params, $routeClass);
    }

    protected function getColumn()
    {
        return 'NetTeam\Bundle\DataTableBundle\Column\TextColumn';
    }

}
