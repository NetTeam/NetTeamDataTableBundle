<?php

namespace NetTeam\System\DataTableBundle\Filter;

use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\AbstractType;

class FilterType extends AbstractType
{

    public function buildForm(FormBuilder $builder, array $options)
    {
        
    }

    public function getName()
    {
        return 'nt_datatable_filter';
    }

}
