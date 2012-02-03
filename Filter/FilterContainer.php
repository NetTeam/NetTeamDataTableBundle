<?php

namespace NetTeam\System\DataTableBundle\Filter;

use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Container for all filter types
 *
 * @author Krzysztof Menżyk <krzysztof.menzyk@netteam.pl>
 */
class FilterContainer
{
    const DEFAULT_TEMPLATE = 'NetTeamDataTableBundle:Filter:filter.html.twig';

    private $filters = array();

    private $factory;
    private $formFactory;

    private $template = self::DEFAULT_TEMPLATE;

    public function __construct(FilterFactory $factory, FormFactoryInterface $formFactory)
    {
        $this->factory = $factory;
        $this->formFactory = $formFactory;
    }

    public function bindRequest(Request $request)
    {
        foreach ($this->filters as $key => $filter) {
            $filter->bindRequest($request);
            $filter->apply();
        }
    }

    public function getFilters()
    {
        return $this->filters;
    }

    public function hasFilters()
    {
        return 0 !== count($this->filters);
    }

    public function addFilter($type, $name, \Closure $callback, $options = array())
    {
        $options['label'] = $name;
        if (!isset($options['required'])) {
            $options['required'] = false;
        }
        $key = count($this->filters);
        $type = $this->factory->create($type);
        $type->setOptions($options);
        $builder = $this->formFactory->createNamedBuilder('form', 'filter-' . $key);

        $this->filters[$key] = new Filter($type, $builder, $callback);
    }

    public function setTemplate($tempalte)
    {
        $this->template = $tempalte;
    }

    public function getTemplate()
    {
        return $this->template;
    }
}
