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

    /**
     * @deprecated
     */
    public function setPriority($priority)
    {
        throw new Exception("Method 'setPriority' is deprecated");
    }

    public function sortable($keys)
    {
        return $this->sortable($keys);
    }

    public function addGetter($getter)
    {
        return $this->column->addGetter($getter);
    }

    public function getValue($objectOrArray)
    {
        return $this->column->getValue($objectOrArray);
    }

    public function class_($class)
    {
        return $this->column->class_($class);
    }

    public function addSortable($key)
    {
        return $this->column->addSortable($key);
    }

    public function width($width)
    {
        return $this->column->width($width);
    }

    public function route($route, array $params, $routeClass = null)
    {
        return $this->column->route($route, $params, $routeClass);
    }

    public function getSortableKeys()
    {
        return $this->column->getSortableKeys();
    }

    public function isSortable()
    {
        return $this->column->isSortable();
    }

    /**
     * @deprecated
     */
    public function setWidth($width)
    {
        throw new Exception("Method 'setWidth' is deprecated");
    }

    public function addClass($class)
    {
        return $this->column->addClass($class);
    }

    /**
     * @deprecated
     */
    public function setRoute($route, array $params, $routeClass = null)
    {
        throw new Exception("Method 'setRoute' is deprecated");
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

    /**
     * @deprecated
     */
    public function setSortable($columns)
    {
        throw new Exception("Method 'setSortable' is deprecated");
    }

    public static function create($name, $getters, array $parameters)
    {
        throw new \BadMethodCallException('You mustn\'t use "create" method on ColumnDecorator');
    }

    public function priority($priority)
    {
        return $this->column->priority($priority);
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
