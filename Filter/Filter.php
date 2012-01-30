<?php

namespace NetTeam\System\DataTableBundle\Filter;

use Symfony\Component\Form\FormBuilder;
use Symfony\Component\HttpFoundation\Request;
use NetTeam\System\DataTableBundle\Filter\Type\FilterTypeInterface;

class Filter
{
    protected $type;
    protected $builder;
    protected $callback;
    protected $model;
    
    public function __construct(FilterTypeInterface $type, FormBuilder $builder, $callback)
    {
        $this->type = $type;
        $this->callback = $callback;
        $this->model = $type->getData();
        
        $this->type->buildForm($builder);
        $this->builder = $builder;
        $this->builder->setData($this->model);
    }
    
    public function getType()
    {
        return $this->type;
    }
    
    public function getCallback()
    {
        return $this->callback;
    }
    
    public function getBuilder()
    {
        return $this->builder;
    }
    
    public function getForm()
    {
        return $this->builder->getForm()->createView();
    }
    
    public function getTemplate()
    {
        return $this->type->getTemplate();
    }
    
    public function apply()
    {
        $this->type->apply($this->callback, $this->model);
    }
    
    public function bindRequest(Request $request)
    {
        $this->builder->getForm()->bindRequest($request);
    }
}
