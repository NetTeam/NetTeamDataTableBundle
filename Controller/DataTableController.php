<?php

namespace NetTeam\System\DataTableBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\Request;
use NetTeam\System\DataTableBundle\DataTable\DataTableFactory;
use NetTeam\System\DataTableBundle\Util\JsonResponseBuilder;
use NetTeam\System\DataTableBundle\DataTable\DataTableBuilder;

class DataTableController
{
    private $factory;
    private $jsonBuilder;
    private $templating;
    private $request;

    public function __construct(DataTableFactory $factory, JsonResponseBuilder $jsonBuilder, EngineInterface $templating, Request $request)
    {
        $this->factory     = $factory;
        $this->jsonBuilder = $jsonBuilder;
        $this->templating  = $templating;
        $this->request     = $request;
    }

    public function process($name)
    {
        if ($this->request->query->has('export')) {
            return $this->export($name);
        } else {
            return $this->data($name);
        }
    }

    public function data($name)
    {
        $builder = $this->factory->create($name, $this->request->query->all());

        $offset = $this->request->get('iDisplayStart');
        $limit = $this->request->get('iDisplayLength');
        $echo = $this->request->get('sEcho');

        $this->updateSearch($builder);
        $this->updateSorting($builder);

        $data = $builder->getDataArray($offset, $limit);
        $columns = $builder->getColumns();
        $count = $builder->countRows();
        
        return $this->jsonBuilder->build($data, $builder, $name, $count, $echo);
    }

    public function export($name)
    {
        $builder = $this->factory->create($name, $this->request->query->all());

        $offset = $this->request->get('iDisplayStart');
        $limit = $this->request->get('iDisplayLength');
        $echo = $this->request->get('sEcho');

        $this->updateSearch($builder);
        $this->updateSorting($builder);

        $data = $builder->getDataAllArray();
        $columns = $builder->getColumns();
        $count = $builder->countRows();

        $export = $builder->getExport($this->request->get('export'));


        $content = $this->templating->render('NetTeamDataTableBundle:Export:export.csv.twig', array(
            'echo' => $echo,
            'data' => $data,
            'count' => $count,
            'columns' => $columns,
            'bulkActions' => $builder->getBulkActions(),
            'bulkColumn' => $builder->getBulkActionsColumn(),
            'alias' => $name,
            'export' => $export
                ));
        $response = new Response($content);
        foreach ($export->getHeaders() as $key => $val) {
            $response->headers->set($key, $val);
        }
        return $response;
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