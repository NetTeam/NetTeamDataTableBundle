<?php

namespace NetTeam\System\DataTableBundle\Twig\Extension;

use Twig_Environment;
use Twig_Function_Method;
use NetTeam\System\DataTableBundle\DataTableFactory;

class DataTableExtension extends \Twig_Extension
{
    private $factory;
    private $environment;

    public function __construct(DataTableFactory $factory)
    {
        $this->factory = $factory;
    }

    public function initRuntime(Twig_Environment $environment)
    {
        $this->environment = $environment;
    }

    public function getFunctions()
    {
        return array(
            'datatable' => new Twig_Function_Method($this, 'datatable', array('is_safe' => array('html')))
        );
    }

    public function datatable($name)
    {
        $datatable = $this->factory->get($name);
        return $this->environment->render('NetTeamDataTableBundle::main.html.twig', array('datatable' => $datatable));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'nt.datatable';
    }
}