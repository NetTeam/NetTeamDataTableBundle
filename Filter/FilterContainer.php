<?php

namespace NetTeam\Bundle\DataTableBundle\Filter;

use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use NetTeam\Bundle\DataTableBundle\Filter\Value\FilterValue;
use Symfony\Component\Form\FormConfigBuilder;

/**
 * Container for all filter types
 *
 * @author Krzysztof Menżyk <krzysztof.menzyk@netteam.pl>
 */
class FilterContainer
{

    const DEFAULT_TEMPLATE = 'NetTeamDataTableBundle::filter.html.twig';

    private $name;
    private $filters = array();
    private $factory;
    private $formFactory;
    private $builder;
    private $model;
    private $template = self::DEFAULT_TEMPLATE;

    public function __construct(FilterFactory $factory, FormFactoryInterface $formFactory)
    {
        $this->factory = $factory;
        $this->formFactory = $formFactory;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function bindRequest(Request $request)
    {
        $this->loadBuilder();

        $this->builder->getForm()->bindRequest($request);
        foreach ($this->filters as $filter) {
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
        $this->loadBuilder();

        if (array_key_exists($name, $this->filters)) {
            throw new \UnexpectedValueException(sprintf('Duplicated filter name %s.', $name));
        }

        FormConfigBuilder::validateName($name);

        if (!array_key_exists('label', $options)) {
            $options['label'] = $name;
        }

        $type = $this->factory->create($type);
        $type->setOptions($options);

        $typeBuilder = $this->formFactory->createNamedBuilder($name, new FilterType());
        $this->builder->add($typeBuilder);
        $this->model->add($name, $type->getData());

        $this->filters[$name] = new Filter($name, $this, $type, $callback);
    }

    public function setTemplate($tempalte)
    {
        $this->template = $tempalte;
    }

    public function getTemplate()
    {
        return $this->template;
    }

    public function getFormView($key)
    {
        $this->loadBuilder();

        return $this->builder->getForm()->createView()->getChild($key);
    }

    public function getBuilder($key)
    {
        $this->loadBuilder();

        return $this->builder->get($key);
    }

    public function getModel($key)
    {
        $this->loadBuilder();

        return $this->model->get($key);
    }

    private function loadBuilder()
    {
        if (null !== $this->builder) {
            return;
        }

        $this->model = new FilterValue();
        $this->builder = $this->formFactory->createNamedBuilder('filter-' . $this->name, 'form', $this->model);
    }

}
