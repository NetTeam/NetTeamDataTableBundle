<?php

namespace NetTeam\Bundle\DataTableBundle\Controller;

use NetTeam\Bundle\DataTableBundle\DataTableEvents;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\Request;
use NetTeam\Bundle\DataTableBundle\DataTable\DataTableFactory;
use NetTeam\Bundle\DataTableBundle\Util\JsonResponseBuilder;
use NetTeam\Bundle\DataTableBundle\DataTable\DataTableBuilder;
use NetTeam\Bundle\DataTableBundle\Event\PostBuildEvent;

class DataTableController
{
    private $factory;
    private $jsonBuilder;
    private $templating;
    private $request;
    private $dispatcher;

    /**
     * @param \NetTeam\Bundle\DataTableBundle\DataTable\DataTableFactory $factory
     * @param \NetTeam\Bundle\DataTableBundle\Util\JsonResponseBuilder   $jsonBuilder
     * @param \Symfony\Bundle\FrameworkBundle\Templating\EngineInterface $templating
     * @param \Symfony\Component\HttpFoundation\Request                  $request
     * @param \Symfony\Component\EventDispatcher\EventDispatcher         $dispatcher
     */

    public function __construct(DataTableFactory $factory, JsonResponseBuilder $jsonBuilder, EngineInterface $templating, Request $request, EventDispatcher $dispatcher)
    {
        $this->factory     = $factory;
        $this->jsonBuilder = $jsonBuilder;
        $this->templating  = $templating;
        $this->request     = $request;
        $this->dispatcher  = $dispatcher;
    }

    /**
     * @param  string                                     $name
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function process($name)
    {
        if ($this->request->query->has('export')) {
            return $this->export($name);
        } else {
            return $this->data($name);
        }
    }

    /**
     * @param  string                                     $name
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function data($name)
    {
        $builder = $this->factory->create($name, $this->request->query->all());

        $builder->updateFilterValues($this->request);

        $offset = $this->request->get('iDisplayStart');
        $limit = $this->request->get('iDisplayLength');
        $echo = $this->request->get('sEcho');
        $filterHash = $builder->setFiltersSession($this->request);

        $this->updateSearch($builder);
        $this->updateSorting($builder);

        $postEvent = new PostBuildEvent($builder, $this->request->query->all());
        $this->dispatcher->dispatch(DataTableEvents::POST_BUILD, $postEvent);

        $count = $builder->countRows();
        $data = $builder->getDataArray($offset, $limit);

        return $this->jsonBuilder->build($data, $builder, $name, $count, $echo, $filterHash);
    }

    /**
     * @param  string                                     $name
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function export($name)
    {
        $builder = $this->factory->create($name, $this->request->query->all());

        $builder->updateFilterValues($this->request);
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
            'actions' => $builder->getActions(),
            'alias' => $name,
            'export' => $export
                ));
        $response = new Response($content);
        foreach ($export->getHeaders() as $key => $val) {
            $response->headers->set($key, $val);
        }

        return $response;
    }

    /**
     * @param \NetTeam\Bundle\DataTableBundle\DataTable\DataTableBuilder $builder
     */
    private function updateSearch(DataTableBuilder $builder)
    {
        if ($this->request->get('sSearch') !== null) {
            $globalSearch = $this->request->get('sSearch');
            $builder->setGlobalSearch($globalSearch);
        }
    }

    /**
     * @param \NetTeam\Bundle\DataTableBundle\DataTable\DataTableBuilder $builder
     */
    private function updateSorting(DataTableBuilder $builder)
    {
        if ($this->request->get('iSortCol_0') !== null) {
            $sortingColumn = $this->request->get('iSortCol_0');
            $sortingDirection = strtoupper($this->request->get('sSortDir_0'));
            $builder->setSorting($sortingColumn, $sortingDirection);
        }
    }
}
