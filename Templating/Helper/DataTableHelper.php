<?php

namespace NetTeam\System\DataTableBundle\Templating\Helper;

use Symfony\Component\Templating\Helper\Helper;
use Symfony\Component\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\Response;
use NetTeam\System\DataTableBundle\DataTable\DataTableFactory;
use NetTeam\System\DataTableBundle\Filter\Filter;

class DataTableHelper extends Helper
{
    private $templating;
    private $factory;

    public function __construct(EngineInterface $templating, DataTableFactory $factory)
    {
        $this->templating = $templating;
        $this->factory = $factory;
    }

    public function render($name, array $options = array())
    {
        $datatable = $this->factory->create($name, $options);
        return $this->templating->render('NetTeamDataTableBundle::main.html.twig', array(
                    'datatable' => $datatable,
                    'alias' => $name
                ));
    }

    public function renderResponse($name, array $options = array())
    {
        return new Response($this->render($name, $options));
    }

    public function renderFilter(Filter $filter, array $options = array())
    {
        return $this->templating->render($filter->getTemplate(), $options = array('filter' => $filter->getForm()));
    }

    public function renderFilterButton(array $options = array())
    {
        return $this->templating->render('NetTeamDataTableBundle:Filter:filter_button.html.twig', array(
            'options' => $options,
        ));
    }
    
    public function renderFilterResetButton(array $options = array())
    {
        return $this->templating->render('NetTeamDataTableBundle:Filter:filter_reset_button.html.twig', array(
            'options' => $options,
        ));
    }

    public function getName()
    {
        return 'nt_datatable';
    }

}