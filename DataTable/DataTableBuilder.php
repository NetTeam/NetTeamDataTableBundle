<?php

namespace NetTeam\Bundle\DataTableBundle\DataTable;
use Symfony\Component\HttpFoundation\Request;
use NetTeam\Bundle\DataTableBundle\Filter\Button;
use NetTeam\Bundle\DataTableBundle\Action\Action;
use NetTeam\Bundle\DataTableBundle\BulkAction\Column as BulkActionColumn;
use NetTeam\Bundle\DataTableBundle\Filter\FilterContainer;
use NetTeam\Bundle\DataTableBundle\Export\ExportInterface;
use NetTeam\Bundle\DataTableBundle\Column\ColumnInterface;
use NetTeam\Bundle\DataTableBundle\Factory\ColumnFactory;
use NetTeam\Bundle\DataTableBundle\Factory\ColumnFactoryAwareInterface;
use NetTeam\Bundle\DataTableBundle\Source\SourceInterface;
use NetTeam\Bundle\DataTableBundle\BulkAction\BulkAction;

/**
 * Description of DataTableBuilder
 *
 * @author Wojciech Kulikowski <wojciech.kulikowski@carrywater.pl>
 */
class DataTableBuilder implements ColumnFactoryAwareInterface
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
    private $actions;
    private $actionsTemplate =
        'NetTeamDataTableBundle::actions.html.twig';
    private $searchableKeys = array();
    private $sortingColumn;
    private $sortingOrder;
    private $jQueryUI = false;
    private $filterContainer;
    private $additionalJSTemplate;

    private $columnFactory;

    private $editInPlaceEditAction;
    private $editInPlaceSaveAction;

    private $buttons;

    /**
     * Unique name of datatable
     * @var string
     */
    private $name;

    /**
     * The context if which datatable exists
     * @var array
     */
    private $context = array();

    /**
     * Set true if state of datatable should be preserved upon navigation.
     *
     * @var boolean
     */
    private $statePreserved = true;

    /**
     * Dostępne rozszerzenia do eksportu danych
     *
     * @var array
     */
    private $exports = array();

    /**
     * @param string          $route  Unique list name
     * @param SourceInterface $source Data source
     * @param string          $name   Data table name
     */
    public function __construct($route, SourceInterface $source, $name = null)
    {
        $this->route = $route;
        $this->requiredRouteParameters = array('name' => $name);
        $this->source = $source;
        $this->name = $name;
        $this->bulkActionsColumn = new BulkActionColumn();
        $this->buttons = array();
    }

    /**
     * Datatable context - identifies same datatable class used in different contexts
     * @param  array                                                      $context
     * @return \NetTeam\Bundle\DataTableBundle\DataTable\DataTableBuilder
     */
    public function setContext(array $context)
    {
        $this->context = $context;

        return $this;
    }

    /**
     * @return array
     */
    public function getContext()
    {
        return $this->context;
    }

    /**
     * @return string
     */
    public function getRoute()
    {
        return $this->route;
    }

     /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
    /**
     *
     * @return array
     */
    public function getRouteParameters()
    {
        return array_merge($this->routeParameters, $this->requiredRouteParameters);
    }

    /**
     *
     * @param  array                                                      $parameters
     * @return \NetTeam\Bundle\DataTableBundle\DataTable\DataTableBuilder
     */
    public function setRouteParameters(array $parameters)
    {
        $this->routeParameters = $parameters;

        return $this;
    }

    /**
     * @param  string                                                     $name
     * @param  string|integer                                             $value
     * @return \NetTeam\Bundle\DataTableBundle\DataTable\DataTableBuilder
     */
    public function addRouteParameter($name, $value)
    {
        $this->routeParameters[$name] = $value;

        return $this;
    }

    /**
     * @return SourceInterface
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * @return \NetTeam\Bundle\DataTableBundle\DataTable\DataTableBuilder
     */
    public function simple()
    {
        $this->isSimple = true;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isSimple()
    {
        return $this->isSimple;
    }

    /**
     * @return \NetTeam\Bundle\DataTableBundle\DataTable\DataTableBuilder
     */
    public function noPagination()
    {
        $this->pagination = false;

        return $this;
    }

    /**
     * @return boolean
     */
    public function hasPagination()
    {
        return $this->pagination;
    }

    /**
     * @return array
     */
    public function getColumns()
    {
        return $this->columns;
    }

    /**
     * @return array
     */
    public function getBulkActions()
    {
        return $this->bulkActions;
    }

    /**
     * @return boolean
     */
    public function hasBulkActions()
    {
        return 0 !== count($this->bulkActions);
    }

    /**
     * @return array
     */
    public function getActions()
    {
        return $this->actions;
    }

    /**
     * @return bool
     */
    public function hasActions()
    {
        return 0 !== count($this->actions);
    }

    /**
     * @return array
     */
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

    /**
     * @return integer
     */
    public function countRows()
    {
        return $this->source->count();
    }

    /**
     * @return integer
     */
    public function countColumns()
    {
        return count($this->columns);
    }

    /**
     * @param  string                                                 $column
     * @param  string                                                 $name       optional parameter
     * @param  string                                                 $getter     optional parameter
     * @param  array                                                  $parameters optional parameter
     * @return \NetTeam\Bundle\DataTableBundle\Column\ColumnInterface
     */
    public function addColumn($column, $name = null, $getter = null, array $parameters = array())
    {
        if (is_object($column) && $column instanceof ColumnInterface) {
            $this->columns[] = $column;

            return $column;
        }

        $column = $this->columnFactory->create($column, $name, $getter, $parameters);
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

    /**
     * @param  array|string                                               $keys
     * @return \NetTeam\Bundle\DataTableBundle\DataTable\DataTableBuilder
     */
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
    /**
     * @param  array|string                                               $keys
     * @return \NetTeam\Bundle\DataTableBundle\DataTable\DataTableBuilder
     */
    public function searchable($keys)
    {
        return $this->setSearchable($keys);
    }

    /**
     * @param  string                                                     $key
     * @return \NetTeam\Bundle\DataTableBundle\DataTable\DataTableBuilder
     * @throws InvalidArgumentException
     */
    public function addSearchableKey($key)
    {
        if (!is_string($key)) {
            throw \InvalidArgumentException('Wrong key definition');
        }
        if (!in_array($key, $this->searchableKeys)) {
            $this->searchableKeys[] = $key;
        }

        return $this;
    }

    /**
     * @param string $search
     */
    public function setGlobalSearch($search)
    {
        $this->globalSearch = $search;
    }

    /**
     * @return boolean
     */
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

    /**
     * @param \NetTeam\Bundle\DataTableBundle\Column\ColumnInterface $column
     * @param string                                                 $order
     */
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
        $this->filtering();
        $this->sorting();
    }

    /**
     *
     * @param  integer $offset
     * @param  integer $limit
     * @return array
     */
    public function getData($offset, $limit)
    {
        $this->prepareData();

        return $this->source->getData($offset, $limit);
    }

    /**
     * @return array
     */
    public function getDataAll()
    {
        $this->prepareData();

        return $this->source->getDataAll();
    }
    /**
     *
     * @param  mixed $data
     * @return array
     */
    private function dataToArray($data)
    {
        $dataArray = array();
        foreach ($data as $row) {
            $dataArray[] = $this->parseRow($row);
        }

        return $dataArray;
    }

    /**
     * @param  integer $offset
     * @param  integer $limit
     * @return array
     */
    public function getDataArray($offset, $limit)
    {
        $data = $this->getData($offset, $limit);

        return $this->dataToArray($data);
    }

    /**
     * @return array
     */
    public function getDataAllArray()
    {
        $data = $this->getDataAll();

        return $this->dataToArray($data);
    }

    /**
     * @param  mixed $row
     * @return array
     */
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

    /**
     * @return \NetTeam\Bundle\DataTableBundle\DataTable\DataTableBuilder
     */
    public function setJQueryUI()
    {
        $this->jQueryUI = true;

        return $this;
    }

    /**
     * @return boolean
     */
    public function hasJQueryUI()
    {
        return $this->jQueryUI;
    }

    /**
     * @param  string                                                $caption
     * @param  string                                                $route
     * @param  array                                                 $params
     * @return \NetTeam\Bundle\DataTableBundle\BulkAction\BulkAction
     */
    public function addBulkAction($caption, $route, array $params = array())
    {
        $bulkAction = new BulkAction($caption, $route, $params);
        $this->bulkActions[] = $bulkAction;

        return $bulkAction;
    }

    /**
     * @param array $actions
     */
    public function addBulkActions(array $actions)
    {
        foreach ($actions as $action) {
            $this->addBulkAction($action[0], $action[1], isset($action[2]) ? $action[2] : array());
        }
    }

    /**
     * Dodawanie pojedyńczej akcji do DataTable
     *
     * @param string $caption
     * @param string $route
     * @param array  $params
     * @return $this
     */
    public function addAction($caption, $route, array $params = array())
    {
        $action = new Action($caption, $route, $params);
        $this->actions[] = $action;

        return $this;
    }

    /**
     * Dodawanie kolekcji akcji do DataTable
     *
     * @param array $actions
     * @return $this
     */
    public function addActions(array $actions)
    {
        foreach ($actions as $action) {
            $this->addAction($action['caption'], $action['route'], isset($action['params']) ? $action['params'] : array());
        }

        return $this;
    }

    /**
     * @param  \NetTeam\Bundle\DataTableBundle\BulkAction\Column          $column
     * @return \NetTeam\Bundle\DataTableBundle\DataTable\DataTableBuilder
     */
    public function setBulkActionsColumn(BulkActionColumn $column)
    {
        $this->bulkActionsColumn = $column;

        return $this;
    }

    /**
     * @return BulkActionColumn
     */
    public function getBulkActionsColumn()
    {
        return $this->bulkActionsColumn;
    }

    /**
     * @param  \NetTeam\Bundle\DataTableBundle\Filter\FilterContainer     $container
     * @return \NetTeam\Bundle\DataTableBundle\DataTable\DataTableBuilder
     */
    public function setFilterContainer(FilterContainer $container)
    {
        $this->filterContainer = $container;

        return $this;
    }

    /**
     *
     * @return \NetTeam\Bundle\DataTableBundle\Filter\FilterContainer
     */
    public function getFilterContainer()
    {
        return $this->filterContainer;
    }

    /**
     * @return boolean
     */
    public function hasFilters()
    {
        if ($this->filterContainer) {
            return $this->filterContainer->hasFilters();
        }

        return false;
    }

    /**
     * @return array
     */
    public function getFilters()
    {
        return $this->filterContainer->getFilters();
    }

    public function updateFilterValues(Request $request)
    {
        if ($this->hasFilters()) {
            $this->filterContainer->bindRequest($request);
        }
    }

    public function setFiltersSession(Request $request)
    {
        $session = $request->getSession();
        foreach ($request->query->all() as $key => $params) {
            if (preg_match('/^filter/', $key)) {
                $dtFilterHash = md5(serialize($params));
                $session->set('dtfilters', array($dtFilterHash => $params));

                return $dtFilterHash;
            }
        }

        return null;
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

    /**
     * @param  string                                                     $field
     * @return \NetTeam\Bundle\DataTableBundle\DataTable\DataTableBuilder
     */
    public function setBulkActionId($field)
    {
        $this->bulkActionsColumn->setGetter($field);

        return $this;
    }

    /**
     * @param  string                                                     $field
     * @return \NetTeam\Bundle\DataTableBundle\DataTable\DataTableBuilder
     */
    public function bulkActionId($field)
    {
        return $this->setBulkActionId($field);
    }

    /**
     * @param  string                                                     $template
     * @return \NetTeam\Bundle\DataTableBundle\DataTable\DataTableBuilder
     */
    public function setFilterTemplate($template)
    {
        $this->filterContainer->setTemplate($template);

        return $this;
    }

    /**
     * @return string
     */
    public function getFilterTemplate()
    {
        return $this->filterContainer->getTemplate();
    }

    /**
     * @param  string                                                     $template
     * @return \NetTeam\Bundle\DataTableBundle\DataTable\DataTableBuilder
     */
    public function setBulkActionsTemplate($template)
    {
        $this->bulkActionsTemplate = $template;

        return $this;
    }

    /**
     * @return string
     */
    public function getBulkActionsTemplate()
    {
        return $this->bulkActionsTemplate;
    }

    /**
     * @param  string                                                     $template
     * @return \NetTeam\Bundle\DataTableBundle\DataTable\DataTableBuilder
     */
    public function setActionsTemplate($template)
    {
        $this->actionsTemplate = $template;

        return $this;
    }

    /**
     * @return string
     */
    public function getActionsTemplate()
    {
        return $this->actionsTemplate;
    }

    /**
     * @param  string                                                     $template
     * @return \NetTeam\Bundle\DataTableBundle\DataTable\DataTableBuilder
     */
    public function setAdditionalJSTemplate($template)
    {
        $this->additionalJSTemplate = $template;

        return $this;
    }

    /**
     * @return string
     */
    public function getAdditionalJSTemplate()
    {
        return $this->additionalJSTemplate;
    }

    /**
     * @return boolean
     */
    public function hasAdditionalJSTemplate()
    {
        return null !== $this->additionalJSTemplate;
    }

    /**
     * @param  \NetTeam\Bundle\DataTableBundle\Factory\ColumnFactory      $columnFactory
     * @return \NetTeam\Bundle\DataTableBundle\DataTable\DataTableBuilder
     */
    public function setColumnFactory(ColumnFactory $columnFactory)
    {
        $this->columnFactory = $columnFactory;

        return $this;
    }

    /**
     * Dodaje button w sekcji filtrów
     *
     * @param string $id
     * @param string $name
     * @param string $class
     */
    public function addButton($id, $name, $class)
    {
        $this->buttons[] = new Button($id, $name, $class);

        return $this;
    }

    /**
     * @return Button array
     */
    public function getButtons()
    {
        return $this->buttons;
    }

    /**
     * @return boolean
     */
    public function isStatePreserved()
    {
        return $this->statePreserved;

    }

    /**
     * @param  boolean                                                    $statePreserved
     * @return \NetTeam\Bundle\DataTableBundle\DataTable\DataTableBuilder
     */
    public function setFilterDataPreserved($statePreserved)
    {
        $this->statePreserved = $statePreserved;

        return $this;
    }

    public function setEditInPlaceEditAction($routing)
    {
        $this->editInPlaceEditAction = $routing;
    }

    public function setEditInPlaceSaveAction($routing)
    {
        $this->editInPlaceSaveAction = $routing;
    }

    public function hasEditInPlaceActions()
    {
        return $this->editInPlaceSaveAction && $this->editInPlaceSaveAction;
    }

    public function getEditInPlaceEditAction()
    {
        return $this->editInPlaceEditAction;
    }

    public function getEditInPlaceSaveAction()
    {
        return $this->editInPlaceSaveAction;
    }

    /**
     * Dodaje rozszerzenie do eksportu danych z datatable
     *
     * @param string $export   Alias serwisu implementującego ExportInterface
     * @param string $filename Nazwa wygenerowanego pliku
     * @param array  $options
     * @return $this
     */
    public function addExport($export, $filename, array $options = array())
    {
        $this->exports[$export] = array(
            'filename' => $filename,
            'options' => $options,
        );

        return $this;
    }

    /**
     * Tablica z rozszerzeniami do eksportu danych z datatable
     *
     * array(
     *     'alias' => array(
     *         'filename' => string,
     *         'options' => array(...),
     *     ),
     *     ...
     * )
     *
     * @return array
     */
    public function getExports()
    {
        return $this->exports;
    }
}
