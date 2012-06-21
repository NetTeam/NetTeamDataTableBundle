<?php

namespace NetTeam\Bundle\DataTableBundle\Filter\Type;

use Symfony\Component\Form\FormBuilder;

interface FilterTypeInterface
{
    public function buildForm(FormBuilder $builder);

    public function getData();

    public function getDefaultOptions();

    public function setOptions(array $options);

    public function getOption($optionKey);

    public function apply(\Closure $callback, $data);

    public function getAlias();
}
