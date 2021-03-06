<?php

namespace NetTeam\Bundle\DataTableBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use NetTeam\Bundle\DataTableBundle\DataTable\DataTableBuilder;

/**
 * Obsługuje tabelę z paginacją i sortowaniem po stronie serwera
 *
 * @author Krzysztof Menżyk <krzysztof.menzyk@carrywater.pl>
 */
abstract class BaseController extends Controller
{
    const DEFAULT_NAME = 'datatable';

    protected $echo;
    protected $offset;
    protected $limit;
    protected $sortingColumn;
    protected $sortingDirection;
    protected $globalSearch;

    /**
     * Builder tabeli
     * @var NetTeam\Bundle\DataTableBundle\DataTableBuilder
     */
    protected $dtb;

    protected $datatableTemplate = "NetTeamDataTableBundle::main.html.twig";

    abstract protected function createDataTable();

    protected function getDataTableBuilder($name, $source)
    {
        $dtb = new DataTableBuilder($name, $source);
        $filterContainer = $this->container->get('nt_datatable.filter_container');
        $filterContainer->setName($name);
        $dtb->setFilterContainer($filterContainer);

        return $dtb;
    }

    public function listAction()
    {
        $request = $this->get('request');

        if ($request->isXmlHttpRequest() && $request->query->has('sEcho')) {
            $request->setRequestFormat('json');

            return $this->dataAction();
        }

        return $this->showAction();
    }

    private function showAction()
    {
        $this->dtb = $this->dataTable();

        return $this->render($this->datatableTemplate, array(
            'datatable' => $this->dtb,
            'alias'     => $this->dtb->getRoute(),
            'bulkActionsTemplate' => $this->dtb->getBulkActionsTemplate(),
            'actionsTemplate' => $this->dtb->getActionsTemplate(),

        ));
    }

    /**
     * Zwraca tablicę przetwarzaną przez Twig'a
     */
    private function dataAction()
    {
        $this->dtb = $this->dataTable();

        $this->offset = $this->get('request')->get('iDisplayStart');
        $this->limit = $this->get('request')->get('iDisplayLength');
        $this->echo = $this->get('request')->get('sEcho');

        $this->updateSearch();
        $this->updateSorting();

        $data = $this->dtb->getDataArray($this->offset, $this->limit);
        $columns = $this->dtb->getColumns();
        $count = $this->dtb->countRows();
        $bulkActions = $this->dtb->getBulkActions();
        $bulkColumn = $this->dtb->getBulkActionsColumn();
        $actions = $this->dtb->getActions();

        return $this->get('nt_datatable.util.json_response')->build($data, $this->dtb, self::DEFAULT_NAME, $count, $this->echo);
    }

    private function dataTable()
    {
        if ($this->dtb === null) {
            $this->dtb = $this->createDataTable();

            if (!$this->dtb instanceof DataTableBuilder) {
                throw new \InvalidArgumentException("createDataTable must return DataTableBuilder");
            }
        }

        return $this->dtb;
    }

    private function updateSearch()
    {
        if ($this->get('request')->get('sSearch') !== null) {
            $this->globalSearch = $this->get('request')->get('sSearch');
            $this->dtb->setGlobalSearch($this->globalSearch);
        }
    }

    private function updateSorting()
    {
        if ($this->get('request')->get('iSortCol_0') !== null) {
            $this->sortingColumn = $this->get('request')->get('iSortCol_0');
            $this->sortingDirection = strtoupper($this->get('request')->get('sSortDir_0'));
            $this->dtb->setSorting($this->sortingColumn, $this->sortingDirection);
        }
    }
}
