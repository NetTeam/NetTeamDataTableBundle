<?php

namespace NetTeam\Bundle\DataTableBundle\Templating\Helper;

use Symfony\Component\Templating\Helper\Helper;
use Symfony\Component\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\Response;
use NetTeam\Bundle\DataTableBundle\DataTable\DataTableFactory;
use NetTeam\Bundle\DataTableBundle\Util\Javascripts;
use Symfony\Component\HttpFoundation\Request;
use NetTeam\Bundle\DataTableBundle\StateStorage\DataTableStateStorageInterface;

class DataTableHelper extends Helper
{
    private $templating;
    private $factory;
    private $request;
    private $stateStorage;
    private $javascripts = array();

    /**
     * @param \Symfony\Component\Templating\EngineInterface                               $templating
     * @param \NetTeam\Bundle\DataTableBundle\DataTable\DataTableFactory                  $factory
     * @param \Symfony\Component\HttpFoundation\Request                                   $request
     * @param \NetTeam\Bundle\DataTableBundle\StateStorage\DataTableStateStorageInterface $filterStorage
     */
    public function __construct(EngineInterface $templating, DataTableFactory $factory, Request $request, DataTableStateStorageInterface $stateStorage)
    {
        $this->templating   = $templating;
        $this->factory      = $factory;
        $this->request      = $request;
        $this->stateStorage = $stateStorage;
    }

    /**
     * @param  string $name
     * @param  array  $options
     * @param  array  $renderOptions
     * @return string
     */
    public function render($name, array $options = array(), array $renderOptions = array())
    {
        $datatableBuilder = $this->factory->create($name, $options);

        $renderOptions = array_merge(
            array(
                'with_js' => true,
            ),
            $renderOptions
        );

        if ($datatableBuilder->isStatePreserved() && $this->stateStorage->has($datatableBuilder) ) {
            $query = $this->stateStorage->get($datatableBuilder);
            $this->request->query->replace($query);
            $datatableBuilder->updateFilterValues($this->request);
        }

        if (!$renderOptions['with_js']) {
            $this->javascripts[] = $this->templating->render('NetTeamDataTableBundle::datatable.js.twig', array(
                'datatable' => $datatableBuilder,
                'alias' => $name
            ));
        }

        return $this->templating->render('NetTeamDataTableBundle::main.html.twig', array(
            'datatable' => $datatableBuilder,
            'alias' => $name,
            'options' => $renderOptions,
            'bulkActionsTemplate' => $datatableBuilder->getBulkActionsTemplate(),
            'actionsTemplate' => $datatableBuilder->getActionsTemplate(),

        ));
    }

    /**
     * @return string
     */
    public function renderJavascripts()
    {
        if (count($this->javascripts) === 0) {
            return;
        }

        return implode("\n", $this->javascripts);
    }

    /**
     * @param  string                                     $name
     * @param  array                                      $options
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function renderResponse($name, array $options = array())
    {
        return new Response($this->render($name, $options));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'nt_datatable';
    }

}
