<?php

namespace NetTeam\System\DataTableBundle\DataTable;

use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use NetTeam\System\DataTableBundle\Source\SourceInterface;
use NetTeam\System\DataTableBundle\Column\ColumnInterface;
use NetTeam\System\DataTableBundle\BulkAction\BulkAction;
use NetTeam\System\DataTableBundle\BulkAction\Column as BulkActionColumn;
use NetTeam\System\DataTableBundle\Test\Filter;

/**
 * Description of DataTableBuilder
 *
 * @author Wojciech Kulikowski <wojciech.kulikowski@carrywater.pl>
 */
class DataTableBuilder
{
    /**
     * Url zwracający dane
     */
    private $route;

    private $routeParameters = array();

    /**
     * Źródło danych
     * @var NetTeam\System\DataTableBundle\Source\SourceInterface
     */
    private $source;

    /**
     * Definicje kolumn
     */
    private $columns = array();

    private $globalSearch;

    private $bulkActions;
    private $bulkActionsColumn;

    private $searchableKeys = array();

    private $sortingColumn;
    private $sortingOrder;

    private $jQueryUI = false;

    /**
     * Konstruktor
     *
     * @param string $route Unikatowa nazwa listy, nazwa routingu w routing.yml
     * @param string $source Żródło danych
     */
    public function __construct($route, SourceInterface $source)
    {
        $this->route = $route;
        $this->source = $source;
        $this->bulkActionsColumn = new BulkActionColumn();
    }

    public function getRoute()
    {
        return $this->route;
    }

    public function getRouteParameters()
    {
        return $this->routeParameters;
    }

    public function setRouteParameters(array $parameters)
    {
        $this->routeParameters = $parameters;
    }

    public function getSource()
    {
        return $this->source;
    }

    public function getColumns()
    {
        return $this->columns;
    }

    public function getBulkActions()
    {
        return $this->bulkActions;
    }

    public function hasBulkActions()
    {
        return 0 !== count($this->bulkActions);
    }

    public function getColumnsSortedByDefault()
    {
        $columns = array();
        foreach ($this->columns as $no => $column) {
            if ($this->hasBulkActions()) {
                $no++;
            }

            if ($column->isSortedByDefault()) {
                $columns[$no] = $column;
            }
        }

        return $columns;
    }

    public function countRows()
    {
        return $this->source->count();
    }

    public function countColumns()
    {
        return count($this->columns);
    }

    /**
     * @param ColumnInterface $column
     */
    public function addColumn(ColumnInterface $column)
    {
        $this->columns[] = $column;
        return $column;
    }

    private function filtering()
    {
        $this->globalSearch();
    }

    private function globalSearch()
    {
        if ($this->hasGlobalSearch() && $this->globalSearch != '') {
            $this->source->globalSearch($this->searchableKeys, $this->globalSearch);
        }
    }

    public function setSearchable($keys)
    {
        if (is_array($keys)) {
            foreach ($keys as $key) {
                $this->addSearchableKey($key);
            }
        } else if (is_string($keys)) {
            $this->addSearchableKey($keys);
        }

        return $this;
    }

    public function searchable($keys)
    {
        return $this->setSearchable($keys);
    }

    public function addSearchableKey($key)
    {
        if (!is_string($key)) {
            throw \InvalidArgumentException('Niepoprawna definicja klucza');
        }
        if (!in_array($key, $this->searchableKeys)) {
            $this->searchableKeys[] = $key;
        }

        return $this;
    }

    public function setGlobalSearch($search)
    {
        $this->globalSearch = $search;
    }

    public function hasGlobalSearch()
    {
        return !empty($this->searchableKeys);
    }

    private function sorting()
    {
        if ($this->sortingColumn !== null) {
            $this->columnSorting($this->columns[$this->sortingColumn], $this->sortingOrder);
        } else {
            $this->defaultSorting();
        }
    }

    /**
     * Sortuje po domyślnej kolumnie
     */
    private function defaultSorting()
    {
        foreach ($this->columns as $column) {
            if ($column->isSortedByDefault()) {
                $this->columnSorting($column, $column->getDefaultSorting());
            }
        }
    }

    private function columnSorting(ColumnInterface $column, $order)
    {
        if ($column->isSortable()) {
            //sprawdzic czy istnieje mozliwosc sortowania na "source" i wyrzucić wyjątkiem jeżeli nie
            //np przez hasMethod
            $keys = $column->getSortableKeys();

            foreach ($keys as $key) {
                $this->source->addSorting($key, $order);
            }
        }
    }

    /**
     * Ustawia wybrane sortowanie
     * @param int $sortingColumn identyfikator sortowanej kolumny
     * @param string $sortingDirection porządek sortowania
     */
    public function setSorting($sortingColumn, $sortingOrder)
    {
        if ($this->hasBulkActions() && $sortingColumn > 0) {
            $sortingColumn--;
        }

        $this->sortingColumn = $sortingColumn;
        $this->sortingOrder = $sortingOrder;
    }

    public function getData($offset, $limit)
    {
        // TODO: pofiltrować
        $this->filtering();

        //posortować
        $this->sorting();

        //wypluć
        return $this->source->getData($offset, $limit);
    }

    public function getDataArray($offset, $limit)
    {
        $data = $this->getData($offset, $limit);
        $dataArray = array();
        foreach ($data as $row) {
            $dataArray[] = $this->parseRow($row);
        }
        return $dataArray;
    }

    private function parseRow($row)
    {
        $parsedRow = array('columns' => array(), 'bulk' => '');
        foreach ($this->columns as $column) {
            $parsedRow['columns'][] = $column->getValue($row);
        }
        $parsedRow['bulk'] = $this->bulkActionsColumn->getValue($row);
        return $parsedRow;
    }

    public function setJQueryUI()
    {
        $this->jQueryUI = true;
        return $this;
    }

    public function hasJQueryUI()
    {
        return $this->jQueryUI;
    }

    public function addBulkAction($caption, $route,array $params = array())
    {
        $bulkAction = new BulkAction($caption, $route, $params);
        $this->bulkActions[] = $bulkAction;
        return $bulkAction;
    }

    public function addBulkActions(array $actions)
    {
        foreach ($actions as $action) {
            $this->addBulkAction($action[0], $action[1], isset($action[2]) ? $action[2] : array());
        }
    }

    public function setBulkActionsColumn(BulkActionColumn $column)
    {
        $this->bulkActionsColumn = $column;
        return $this;
    }

    public function getBulkActionsColumn()
    {
        return $this->bulkActionsColumn;
    }
}
