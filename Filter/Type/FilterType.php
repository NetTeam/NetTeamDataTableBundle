<?php

namespace NetTeam\System\DataTableBundle\Filter\Type;

use Symfony\Component\Form\FormBuilder;
use NetTeam\System\DataTableBundle\Filter\Type\FilterTypeInterface;

abstract class FilterType implements FilterTypeInterface
{
    protected $options = array();

    function setOptions(array $options)
    {
        $this->options = $options;
    }

    function getOption($optionKey)
    {
        return $this->options[$optionKey];
    }

}