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

    /**
     * @param \NetTeam\Bundle\DataTableBundle\Filter\FilterFactory $factory
     * @param \Symfony\Component\Form\FormFactoryInterface         $formFactory
     */
    public function __construct(FilterFactory $factory, FormFactoryInterface $formFactory)
    {
        $this->factory = $factory;
        $this->formFactory = $formFactory;
    }

    /**
     * @param  string                                                 $name
     * @return \NetTeam\Bundle\DataTableBundle\Filter\FilterContainer
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     */
    public function bindRequest(Request $request)
    {
        $this->loadBuilder();

        $this->builder->getForm()->bind($request);
        foreach ($this->filters as $filter) {
            $filter->apply();
        }
    }
    /**
     * @return array
     */
    public function getFilters()
    {
        return $this->filters;
    }

    /**
     * @return boolean
     */
    public function hasFilters()
    {
        return 0 !== count($this->filters);
    }

    /**
     * @param string   $type
     * @param string   $name
     * @param \Closure $callback
     * @param array    $options
     */
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

    /**
     * @param  string                                                 $tempalte
     * @return \NetTeam\Bundle\DataTableBundle\Filter\FilterContainer
     */
    public function setTemplate($tempalte)
    {
        $this->template = $tempalte;

        return $this;
    }

    /**
     * @return string
     */
    public function getTemplate()
    {
        return $this->template;
    }
    /**
     *
     * @param  type                             $key
     * @return \Symfony\Component\Form\FormView The child view
     */
    public function getFormView($key)
    {
        $this->loadBuilder();

        return $this->builder->getForm()->createView()->children[$key];
    }

    /**
     * @param  type                                $key
     * @return \Symfony\Component\Form\FormBuilder
     */
    public function getBuilder($key)
    {
        $this->loadBuilder();

        return $this->builder->get($key);
    }

    /**
     * @param  string $key
     * @return mixed  | null
     */
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
