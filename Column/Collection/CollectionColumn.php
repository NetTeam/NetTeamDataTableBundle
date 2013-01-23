<?php

namespace NetTeam\Bundle\DataTableBundle\Column\Collection;

use Doctrine\Common\Collections\ArrayCollection;
use NetTeam\Bundle\DataTableBundle\Column\Column;
use NetTeam\Bundle\DataTableBundle\Column\ColumnInterface;

/**
 * Kolumna z kolekcją kolumn
 *
 * @author Piotr Walków piotr.walkow@netteam.pl>
 */
class CollectionColumn extends Column
{
    protected $template = 'collection_column';
    protected $separator = ' ';
    protected $columnCollection;

    public function __construct($caption, $getters = null, $parameters = array())
    {
        $this->caption = $caption;
        $this->parameters = $parameters;

        $this->columnCollection = new ArrayCollection();
    }

    public function add(ColumnInterface $column)
    {
        $this->columnCollection->add($column);

        return new CollectionColumnDecorator($column, $this);
    }

    public function getValue($objectOrArray)
    {
        $value = parent::getValue($objectOrArray);

        $collection = array();

        foreach ($this->columnCollection as $column) {
            $collection[] = array(
                'template' => $column->getTemplate(),
                'record' => $column->getValue($objectOrArray),
            );
        }
        $value->add('collection', $collection);
        $value->add('separator', $this->separator);

        return $value;
    }

    public function getColumnCollection()
    {
        return $this->columnCollection;
    }

    public function setSeparator($separator)
    {
        $this->separator = $separator;

        return $this;
    }

    public function separator($separator)
    {
        return $this->setSeparator($separator);
    }

    public function separatorNewLine()
    {
        return $this->setSeparator('<br />');
    }

}
