<?php

namespace NetTeam\System\DataTableBundle\Filter\Type;

use Symfony\Component\Form\FormBuilder;
use NetTeam\System\DataTableBundle\Filter\Type\FilterTypeInterface;

abstract class FilterType implements FilterTypeInterface
{

    protected $options = array();

    function getDefaultOptions()
    {
        
    }

    function setOptions(array $options)
    {
        $this->options = array_merge($this->getDefaultOptions(), $options);
    }

    function getOption($optionKey)
    {
        return isset($this->options[$optionKey]) ? $this->options[$optionKey] : null;
    }

}