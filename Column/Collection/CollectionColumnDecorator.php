<?php

namespace NetTeam\Bundle\DataTableBundle\Column\Collection;

use NetTeam\Bundle\DataTableBundle\Column\ColumnInterface;

/**
 * Dekorator dla kolumny kolekcji kolumn
 * Zawiera dodatkową metodę 'next()'
 *
 * @author Piotr Walków
 */
class CollectionColumnDecorator implements ColumnInterface
{
    protected $column;
    protected $collectionColumn;

    public function __construct(ColumnInterface $column, CollectionColumn $collectionColumn)
    {
        $this->column = $column;
        $this->collectionColumn = $collectionColumn;
    }

    /**
     * Pozwala na powrót do CollectionColumn
     *
     * @return CollectionColumn
     */
    public function next()
    {
        return $this->collectionColumn;
    }

    public function getParameters()
    {
        return $this->column->getParameters();
    }

    public function sortByDefault($order = 'ASC')
    {
        return $this->column->sortByDefault($order);
    }

    public function setPriority($priority)
    {
        return $this->column->setPriority($priority);
    }

    /**
     * @deprecated
     */
    public function sortable($keys)
    {
        throw new Exception("Method 'sortable' is deprecated");
    }

    public function addGetter($getter)
    {
        return $this->column->addGetter($getter);
    }

    public function getValue($objectOrArray)
    {
        return $this->column->getValue($objectOrArray);
    }

    /**
     * @deprecated
     */
    public function class_($class)
    {
        throw new Exception("Method 'class_' is deprecated");
    }

    public function addSortable($key)
    {
        return $this->column->addSortable($key);
    }

    /**
     * @deprecated
     */
    public function width($width)
    {
        throw new Exception("Method 'width' is deprecated");
    }

    /**
     * @deprecated
     */
    public function route($route, array $params, $routeClass = null)
    {
        throw new Exception("Method 'route' is deprecated");
    }

    public function getSortableKeys()
    {
        return $this->column->getSortableKeys();
    }

    public function isSortable()
    {
        return $this->column->isSortable();
    }

    public function setWidth($width)
    {
        return $this->column->setWidth($width);
    }

    public function addClass($class)
    {
        return $this->column->addClass($class);
    }

    public function setRoute($route, array $params, $routeClass = null)
    {
        return $this->column->setRoute($route, $params, $routeClass);
    }

    public function isSortedByDefault()
    {
        return $this->column->isSortedByDefault();
    }

    public function hasWidth()
    {
        return $this->column->hasWidth();
    }

    public function getTemplate()
    {
        return $this->column->getTemplate();
    }

    public function getWidth()
    {
        return $this->column->getWidth();
    }

    public function getter($getter)
    {
        return $this->column->getter($getter);
    }

    public function getDefaultSorting()
    {
        return $this->column->getDefaultSorting();
    }

    public function getCaption()
    {
        return $this->column->getCaption();
    }

    public function setSortable($keys)
    {
       return $this->column->setSortable($columns);
    }

    public static function create($name, $getters, array $parameters)
    {
        throw new \BadMethodCallException('You mustn\'t use "create" method on ColumnDecorator');
    }

    /**
     * @deprecated
     */
    public function priority($priority)
    {
        throw new Exception("Method 'priority' is deprecated");
    }

    public function getPriority()
    {
        return $this->column->getPriority();
    }

    public function getClass()
    {
        return $this->column->getClass();
    }

}
