<?php

namespace NetTeam\Bundle\DataTableBundle\Column\Collection;

use NetTeam\Bundle\DataTableBundle\Column\Column;
use NetTeam\Bundle\DataTableBundle\Column\ColumnInterface;
use NetTeam\Bundle\DataTableBundle\Factory\ColumnFactory;
use NetTeam\Bundle\DataTableBundle\Factory\ColumnFactoryAwareInterface;

/**
 * Kolumna z kolekcją kolumn
 *
 * @author Piotr Walków piotr.walkow@netteam.pl>
 */
class CollectionColumn extends Column implements ColumnFactoryAwareInterface
{
    /**
     * @var string Nazwa domyslnego szablonu
     */
    protected $template = 'collection_column';

    /**
     * @var separator dla kolumn w kolekcji
     */
    protected $separator = ' ';

    /**
     * @var array Kolekcja kolumn
     */
    protected $columnCollection = array();

    /**
     * @var ColumnFactory serwis fabryki kolumn
     */
    protected $columnFactory;

    public function __construct($caption, $getters = null, $parameters = array())
    {
        $this->caption = $caption;
        $this->parameters = $parameters;
    }

    /**
     * @inheritdoc
     */
    public function setColumnFactory(ColumnFactory $columnFactory)
    {
        $this->columnFactory = $columnFactory;
    }

    /**
     * Dodanie kolumny do kolekcji
     *
     * @param  \NetTeam\Bundle\DataTableBundle\Column\ColumnInterface                      $column Kolumna do dodania
     * @return \NetTeam\Bundle\DataTableBundle\Column\Collection\CollectionColumnDecorator
     */
    public function add($column, $name = null, $getter = null, array $parameters = array())
    {
        $column = $this->columnFactory->create($column, $name, $getter, $parameters);

        $this->columnCollection[] = $column;

        return new CollectionColumnDecorator($column, $this);
    }

    /**
     * Pobranie wartości ze wszystkich kolumn
     *
     * @param  array|object $objectOrArray
     * @return object
     */
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

    /**
     * Pobiera listę kolumn w tej kolekcji
     *
     * @return Column[]
     */
    public function getColumnCollection()
    {
        return $this->columnCollection;
    }

    /**
     * Zmiana separatora
     *
     * @param  string $separator
     * @return self
     */
    public function setSeparator($separator)
    {
        $this->separator = $separator;

        return $this;
    }

    /**
     * Ustawia separator na nową linię
     *
     * @return self
     */
    public function setSeparatorNewLine()
    {
        return $this->setSeparator('<br />');
    }

}
