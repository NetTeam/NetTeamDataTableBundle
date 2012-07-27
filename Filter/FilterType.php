<?php

namespace NetTeam\Bundle\DataTableBundle\Filter;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\AbstractType;

class FilterType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

    }

    public function getName()
    {
        return 'nt_datatable_filter';
    }

}
