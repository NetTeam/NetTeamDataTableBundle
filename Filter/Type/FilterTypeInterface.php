<?php

namespace NetTeam\System\DataTableBundle\Filter\Type;

use Symfony\Component\Form\FormBuilder;

interface FilterTypeInterface
{

    function buildForm(FormBuilder $builder);

    function getData();

    function getDefaultOptions();

    function setOptions(array $options);

    function getOption($optionKey);

    function apply(\Closure $callback, $data);

    function getTemplate();
}