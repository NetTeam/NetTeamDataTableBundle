<?php

namespace NetTeam\System\DataTableBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\Request;
use NetTeam\System\DataTableBundle\DataTable\DataTableFactory;
use NetTeam\System\DataTableBundle\DataTable\DataTableBuilder;

class DataTableController
{
    private $factory;
    private $templating;
    private $request;

    public function __construct(DataTableFactory $factory, EngineInterface $templating, Request $request)
    {
        $this->factory    = $factory;
        $this->templating = $templating;
        $this->request    = $request;
    }

    public function process($name)
    {
        $builder = $this->factory->create($name);

        $offset = $this->request->get('iDisplayStart');
        $limit = $this->request->get('iDisplayLength');
        $echo = $this->request->get('sEcho');

        $this->updateSearch($builder);
        $this->updateSorting($builder);

        $data = $builder->getDataArray($offset, $limit);
        $columns = $builder->getColumns();
        $count = $builder->countRows();

        return $this->templating->renderResponse('NetTeamDataTableBundle::data.json.twig', array(
            'echo' => $echo,
            'data' => $data,
            'count' => $count,
            'columns' => $columns,
            'bulkActions' => $builder->getBulkActions(),
            'bulkColumn' => $builder->getBulkActionsColumn(),
            'alias' => $name
        ));
    }

    private function updateSearch(DataTableBuilder $builder)
    {
        if ($this->request->get('sSearch') !== null) {
            $globalSearch = $this->request->get('sSearch');
            $builder->setGlobalSearch($globalSearch);
        }
    }

    private function updateSorting(DataTableBuilder $builder)
    {
        if ($this->request->get('iSortCol_0') !== null) {
            $sortingColumn = $this->request->get('iSortCol_0');
            $sortingDirection = strtoupper($this->request->get('sSortDir_0'));
            $builder->setSorting($sortingColumn, $sortingDirection);
        }
    }
}