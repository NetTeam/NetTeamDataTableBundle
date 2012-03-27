<?php

namespace NetTeam\Bundle\DataTableBundle\Twig\Extension;

use Twig_Function_Method;
use Symfony\Component\DependencyInjection\ContainerInterface;
use NetTeam\Bundle\DataTableBundle\Filter\Filter;

class DataTableFilterExtension extends \Twig_Extension
{
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function getFunctions()
    {
        return array(
            'datatable_filter' => new Twig_Function_Method($this, 'datatableFilter', array('is_safe' => array('html'))),
            'datatable_filter_button' => new Twig_Function_Method($this, 'datatableFilterButton', array('is_safe' => array('html'))),
            'datatable_filter_reset_button' => new Twig_Function_Method($this, 'datatableFilterResetButton', array('is_safe' => array('html'))),
        );
    }

    public function datatableFilter(Filter $filter)
    {
        return $this->container->get('nt_datatable.templating.helper')->renderFilter($filter);
    }
    
    public function datatableFilterButton(array $options = array())
    {
        return $this->container->get('nt_datatable.templating.helper')->renderFilterButton($options);
    }
    
    public function datatableFilterResetButton(array $options = array())
    {
        return $this->container->get('nt_datatable.templating.helper')->renderFilterResetButton($options);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'nt_datatable_filter';
    }
}