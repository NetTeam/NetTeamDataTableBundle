<?php

namespace NetTeam\Bundle\DataTableBundle\Twig\Extension;

use Twig_Function_Method;
use NetTeam\Bundle\DataTableBundle\DataTable\DataTableBuilder;
use NetTeam\Bundle\DataTableBundle\Filter\Filter;

class DataTableFilterExtension extends \Twig_Extension
{
    protected $environment;

    public function initRuntime(\Twig_Environment $environment)
    {
        $this->environment = $environment;
    }

    public function getFunctions()
    {
        return array(
            'datatable_filters' => new Twig_Function_Method($this, 'datatableFilters', array('is_safe' => array('html'))),
            'datatable_filter' => new Twig_Function_Method($this, 'datatableFilter', array('is_safe' => array('html'))),
        );
    }

    public function datatableFilters(DataTableBuilder $datatable, $alias)
    {
        $template = $this->environment->loadTemplate($datatable->getFilterTemplate());

        return $template->renderBlock('filters', array(
            'filters' => $datatable->getFilters(),
            'buttons' => $datatable->getButtons(),
            'alias' => $alias,
            'template' => $template,
        ));
    }

    public function datatableFilter(Filter $filter, $template)
    {
        if (!$template instanceof \Twig_Template) {
            $template = $this->environment->loadTemplate($template);
        }

        $alias = $filter->getType()->getAlias();
        $block = $alias.'_filter';

        if (!$template->hasBlock($block)) {
            $block = 'default_filter';
        }

        return $template->renderBlock($block, array('filter' => $filter->getForm()));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'nt_datatable_filter';
    }
}
