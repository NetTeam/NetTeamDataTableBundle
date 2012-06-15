<?php

namespace NetTeam\Bundle\DataTableBundle\Templating\Helper;

use Symfony\Component\Templating\Helper\Helper;
use Symfony\Component\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\Response;
use NetTeam\Bundle\DataTableBundle\DataTable\DataTableFactory;
use NetTeam\Bundle\DataTableBundle\Util\Javascripts;
use NetTeam\Bundle\DataTableBundle\Filter\Filter;

class DataTableHelper extends Helper
{
    private $templating;
    private $factory;
    private $javascripts = array();

    public function __construct(EngineInterface $templating, DataTableFactory $factory)
    {
        $this->templating  = $templating;
        $this->factory     = $factory;
    }

    public function render($name, array $options = array(), array $renderOptions = array())
    {
        $datatable = $this->factory->create($name, $options);

        $renderOptions = array_merge(
            array(
                'with_js' => true,
            ),
            $renderOptions
        );

        if (!$renderOptions['with_js']) {
            $this->javascripts[] = $this->templating->render('NetTeamDataTableBundle::datatable.js.twig', array(
                'datatable' => $datatable,
                'alias' => $name
            ));
        }

        return $this->templating->render('NetTeamDataTableBundle::main.html.twig', array(
            'datatable' => $datatable,
            'alias' => $name,
            'options' => $renderOptions,
        ));
    }

    public function renderJavascripts()
    {
        if (count($this->javascripts) === 0) {
            return;
        }

        return implode("\n", $this->javascripts);
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
