<?php

namespace NetTeam\System\DataTableBundle\Templating\Helper;

use Symfony\Component\Templating\Helper\Helper;
use Symfony\Component\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\Response;
use NetTeam\System\DataTableBundle\DataTable\DataTableFactory;

class DataTableHelper extends Helper
{
    private $templating;
    private $factory;

    public function __construct(EngineInterface $templating, DataTableFactory $factory)
    {
        $this->templating = $templating;
        $this->factory    = $factory;
    }

    public function render($name)
    {
        $datatable = $this->factory->create($name);
        return $this->templating->render('NetTeamDataTableBundle::main.html.twig', array('datatable' => $datatable));
    }

    public function renderResponse($name)
    {
        return new Response($this->render($name));
    }

    public function getName()
    {
        return 'nt_datatable';
    }
}