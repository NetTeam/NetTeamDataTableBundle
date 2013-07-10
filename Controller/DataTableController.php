<?php

namespace NetTeam\Bundle\DataTableBundle\Controller;

use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\Request;
use NetTeam\Bundle\DataTableBundle\DataTableEvents;
use NetTeam\Bundle\DataTableBundle\DataTable\DataTableFactory;
use NetTeam\Bundle\DataTableBundle\Util\JsonResponseBuilder;
use NetTeam\Bundle\DataTableBundle\DataTable\DataTableBuilder;
use NetTeam\Bundle\DataTableBundle\Event\PostBuildEvent;
use NetTeam\Bundle\DataTableBundle\Export\ExportContainer;

class DataTableController
{
    /**
     * @var DataTableFactory
     */
    private $factory;

    /**
     * @var JsonResponseBuilder
     */
    private $jsonBuilder;

    /**
     * @var EngineInterface
     */
    private $templating;

    /**
     * @var Request
     */
    private $request;

    /**
     * @var EventDispatcher
     */
    private $dispatcher;

    /**
     * @var ExportContainer
     */
    private $exportContainer;

    /**
     * @param \NetTeam\Bundle\DataTableBundle\DataTable\DataTableFactory $factory
     * @param \NetTeam\Bundle\DataTableBundle\Util\JsonResponseBuilder   $jsonBuilder
     * @param \Symfony\Bundle\FrameworkBundle\Templating\EngineInterface $templating
     * @param \Symfony\Component\HttpFoundation\Request                  $request
     * @param \Symfony\Component\EventDispatcher\EventDispatcher         $dispatcher
     * @param ExportService                                              $xlsExportService
     */
    public function __construct(DataTableFactory $factory, JsonResponseBuilder $jsonBuilder, EngineInterface $templating, Request $request, EventDispatcher $dispatcher, ExportContainer $exportContainer)
    {
        $this->factory     = $factory;
        $this->jsonBuilder = $jsonBuilder;
        $this->templating  = $templating;
        $this->request     = $request;
        $this->dispatcher  = $dispatcher;
        $this->exportContainer = $exportContainer;
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

        $this->updateSearch($builder);
        $this->updateSorting($builder);

        $postEvent = new PostBuildEvent($builder, $this->request->query->all());
        $this->dispatcher->dispatch(DataTableEvents::POST_BUILD, $postEvent);

        $data = $builder->getDataArray($offset, $limit);
        $count = $builder->countRows();

        return $this->jsonBuilder->build($data, $builder, $name, $count, $echo);
    }

    /**
     * @param  string   $name
     * @return Response
     */
    public function export($name)
    {
        $builder = $this->factory->create($name, $this->request->query->all());
        $builder->updateFilterValues($this->request);

        $this->updateSearch($builder);
        $this->updateSorting($builder);

        $exportType = $this->request->query->get('export');
        $exports = $builder->getExports($exportType);

        if (!array_key_exists($exportType, $exports)) {
            throw new \InvalidArgumentException(sprintf('DataTable "%s" does not support "%s" export', $name, $exportType));
        }

        $exportConfig = $exports[$exportType];
        $exporter = $this->exportContainer->get($exportType);

        return $exporter->export($exportConfig['filename'], $builder->getColumns(), $builder->getDataAllArray(), $exportConfig['options']);
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
