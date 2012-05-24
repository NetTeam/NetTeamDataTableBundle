<?php

namespace NetTeam\Bundle\DataTableBundle\Filter\Type;

use NetTeam\Bundle\DataTableBundle\Filter\Type\FilterTypeInterface;

abstract class FilterType implements FilterTypeInterface
{

    protected $options = array();

    public function getDefaultOptions()
    {

    }

    public function setOptions(array $options)
    {
        $this->options = array_merge($this->getDefaultOptions(), $options);
    }

    public function getOption($optionKey)
    {
        return isset($this->options[$optionKey]) ? $this->options[$optionKey] : null;
    }

}
