<?php

namespace NetTeam\Bundle\DataTableBundle\DataTable;

use Symfony\Component\HttpFoundation\Request;
use NetTeam\Bundle\DataTableBundle\Source\SourceInterface;
use NetTeam\Bundle\DataTableBundle\Column\ColumnInterface;
use NetTeam\Bundle\DataTableBundle\BulkAction\BulkAction;
use NetTeam\Bundle\DataTableBundle\BulkAction\Column as BulkActionColumn;
use NetTeam\Bundle\DataTableBundle\Filter\FilterContainer;
use NetTeam\Bundle\DataTableBundle\Export\CsvExport;
use NetTeam\Bundle\DataTableBundle\Export\ExportInterface;
use NetTeam\Bundle\DataTableBundle\Column\ColumnFactory;

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
    private $requiredRouteParameters = array();
    private $routeParameters = array();

    /**
     * Źródło danych
     * @var NetTeam\Bundle\DataTableBundle\Source\SourceInterface
     */
    private $source;
    private $isSimple = false;
    private $pagination = true;

    /**
     * Definicje kolumn
     */
    private $columns = array();
    private $globalSearch;
    private $bulkActions;
    private $bulkActionsColumn;
    private $bulkActionsTemplate =
        'NetTeamDataTableBundle::bulk_actions.html.twig';

    private $exports;
    private $searchableKeys = array();
    private $sortingColumn;
    private $sortingOrder;
    private $jQueryUI = false;
    private static $exportTypes = array(
        'csv' => 'NetTeam\Bundle\DataTableBundle\Export\CsvExport'
    );
    private $filterContainer;
    private $additionalJSTemplate;

    /**
     * Konstruktor
     *
     * @param string $route  Unikatowa nazwa listy, nazwa routingu w routing.yml
     * @param string $source Żródło danych
     */
    public function __construct($route, SourceInterface $source, array $requiredRouteParameters = array())
    {
        $this->route = $route;
        $this->requiredRouteParameters = $requiredRouteParameters;
        $this->source = $source;
        $this->bulkActionsColumn = new BulkActionColumn();
        $this->exports = array();
    }

    public function getRoute()
    {
        return $this->route;
    }

    public function getRouteParameters()
    {
        return array_merge($this->routeParameters, $this->requiredRouteParameters);
    }

    public function getRouteExportParameters($exportName)
    {
        return array_merge($this->routeParameters, $this->requiredRouteParameters, array('export' => $exportName));
    }

    public function setRouteParameters(array $parameters)
    {
        $this->routeParameters = $parameters;
    }

    public function addRouteParameter($name, $value)
    {
        $this->routeParameters[$name] = $value;
    }

    public function getSource()
    {
        return $this->source;
    }

    public function simple()
    {
        $this->isSimple = true;

        return $this;
    }

    public function isSimple()
    {
        return $this->isSimple;
    }

    public function noPagination()
    {
        $this->pagination = false;

        return $this;
    }

    public function hasPagination()
    {
        return $this->pagination;
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

    public function setExportOption($name, $key, $value)
    {
        $this->export[$name]->setOptions($key, $value);
    }

    public function addExport($name, $type, $options = array())
    {
        if (self::$exportTypes[$type]) {
            $this->exports[$name] = new self::$exportTypes[$type];
            $this->exports[$name]->setOptions($options);
        }
    }

    public function getExports()
    {
        return $this->exports;
    }

    public function buildExports()
    {
        foreach ($this->exports as $export) {
            if (!($export instanceof ExportInterface)) {
                throw new \Exception('export should be instance of ExportInterface');
            }
            $export->build();
        }
    }

    public function getExport($name)
    {
        //sprawdzić czy istnieje
        return $this->exports[$name];
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
     * @param $column
     */
    public function addColumn($column, $name = null, $getter = null, array $parameters = array())
    {
        if (is_object($column) && $column instanceof ColumnInterface) {
            $this->columns[] = $column;

            return $column;
        }

        $columnFactory = new ColumnFactory();
        $column = $columnFactory->create($column, $name, $getter, $parameters);
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
        } elseif (is_string($keys)) {
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
     * @param int    $sortingColumn    identyfikator sortowanej kolumny
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

    private function prepareData()
    {
        // TODO: pofiltrować
        $this->filtering();

        //posortować
        $this->sorting();
    }

    public function getData($offset, $limit)
    {
        $this->prepareData();

        return $this->source->getData($offset, $limit);
    }

    public function getDataAll()
    {
        $this->prepareData();

        return $this->source->getDataAll();
    }

    private function dataToArray($data)
    {
        $dataArray = array();
        foreach ($data as $row) {
            $dataArray[] = $this->parseRow($row);
        }

        return $dataArray;
    }

    public function getDataArray($offset, $limit)
    {
        $data = $this->getData($offset, $limit);

        return $this->dataToArray($data);
    }

    public function getDataAllArray()
    {
        $data = $this->getDataAll();

        return $this->dataToArray($data);
    }

    private function parseRow($row)
    {
        $parsedRow = array('columns' => array(), 'bulk' => '', 'routeParams' => array());
        foreach ($this->columns as $column) {
            $parsedRow['columns'][] = $column->getValue($row);
        }

        if (!empty($this->bulkActions)) {
            $parsedRow['bulk'] = $this->bulkActionsColumn->getValue($row);
        }

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

    public function addBulkAction($caption, $route, array $params = array())
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

    public function setFilterContainer(FilterContainer $container)
    {
        $this->filterContainer = $container;
    }

    public function hasFilters()
    {
        if ($this->filterContainer) {
            return $this->filterContainer->hasFilters();
        }

        return false;
    }

    public function getFilters()
    {
        return $this->filterContainer->getFilters();
    }

    public function updateFilterValues(Request $request)
    {
        return $this->filterContainer->bindRequest($request);
    }

    /**
     * Dopisuje filtr do datatable
     * @param  string           $type     typ filtru, domyslny: default
     * @param  string           $name     label przy filtrze
     * @param  \Closure         $callback
     * @return DataTableBuilder
     */
    public function addFilter($type = 'default', $name, \Closure $callback, array $options = array())
    {
        $this->filterContainer->addFilter($type, $name, $callback, $options);

        return $this;
    }

    public function setBulkActionId($field)
    {
        $this->bulkActionsColumn->setGetter($field);

        return $this;
    }

    public function bulkActionId($field)
    {
        return $this->setBulkActionId($field);
    }

    public function setFilterTemplate($template)
    {
        $this->filterContainer->setTemplate($template);
    }

    public function getFilterTemplate()
    {
        return $this->filterContainer->getTemplate();
    }

    public function setBulkActionsTemplate($template)
    {
        $this->bulkActionsTemplate = $template;
    }

    public function getBulkActionsTemplate()
    {
        return $this->bulkActionsTemplate;
    }

    public function setAdditionalJSTemplate($template)
    {
        $this->additionalJSTemplate = $template;
    }

    public function getAdditionalJSTemplate()
    {
        return $this->additionalJSTemplate;
    }

    public function hasAdditionalJSTemplate()
    {
        return null !== $this->additionalJSTemplate;
    }

}
