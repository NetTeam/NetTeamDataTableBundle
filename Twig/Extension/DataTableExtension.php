<?php

namespace NetTeam\Bundle\DataTableBundle\Twig\Extension;

use Twig_Function_Method;
use Symfony\Component\DependencyInjection\ContainerInterface;

class DataTableExtension extends \Twig_Extension
{
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function getFunctions()
    {
        return array(
            'datatable' => new Twig_Function_Method($this, 'datatable', array('is_safe' => array('html'))),
            'datatable_javascripts' => new Twig_Function_Method($this, 'datatableJavascripts', array('is_safe' => array('html'))),
        );
    }

    public function datatable($name, array $options = array(), array $renderOptions = array())
    {
        return $this->container->get('nt_datatable.templating.helper')->render($name, $options, $renderOptions);
    }

    public function datatableJavascripts()
    {
        return $this->container->get('nt_datatable.templating.helper')->renderJavascripts();
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'nt_datatable';
    }
}
