<?php

namespace NetTeam\Bundle\DataTableBundle\Column\Collection;

use Doctrine\Common\Collections\ArrayCollection;
use NetTeam\Bundle\DataTableBundle\Column\Column;
use NetTeam\Bundle\DataTableBundle\Column\ColumnInterface;
use NetTeam\Bundle\DataTableBundle\Column\ColumnFactory;
use NetTeam\Bundle\DataTableBundle\Column\ColumnFactoryAwareInterface;


/**
 * Kolumna z kolekcją kolumn
 *
 * @author Piotr Walków piotr.walkow@netteam.pl>
 */
class CollectionColumn extends Column implements ColumnFactoryAwareInterface
{
    protected $template = 'collection_column';
    protected $separator = ' ';
    protected $columnCollection = array();
    protected $columnFactory;

    public function __construct($caption, $getters = null, $parameters = array())
    {
        $this->caption = $caption;
        $this->parameters = $parameters;
    }
    
    public function setColumnFactory(ColumnFactory $columnFactory)
    {
        $this->columnFactory = $columnFactory;
    }
    
    /**
     * Dodanie kolumny do kolekcji
     * 
     * @param \NetTeam\Bundle\DataTableBundle\Column\ColumnInterface $column Kolumna do dodania
     * @return \NetTeam\Bundle\DataTableBundle\Column\Collection\CollectionColumnDecorator
     */
    public function add($column, $name = null, $getter = null, array $parameters = array())
    {
        if (is_object($column) && $column instanceof ColumnInterface) {
            $this->columnCollection[] = $column;
        }
        else {
            $column = $this->columnFactory->create($column, $name, $getter, $parameters);
            $this->columnCollection[] = $column;
        }
        
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
