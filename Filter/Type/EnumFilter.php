<?php

namespace NetTeam\Bundle\DataTableBundle\Filter\Type;

use Symfony\Component\Form\FormBuilder;
use NetTeam\Bundle\DataTableBundle\Filter\Value\FilterValue;
use NetTeam\Bundle\DataTableBundle\Filter\Type\FilterType;
use NetTeam\DDD\Enum;

/**
 * EnumFilter
 *
 * @author Dawid Drelichowski <dawid.drelichowski@netteam.pl>
 */
class EnumFilter extends FilterType
{

    public function buildForm(FormBuilder $builder)
    {
        $attr = array_merge($this->getOption('attr'), array('data-filter-default' => $this->getOption('default')));
        $options = array(
            'required' => $this->getOption('required'),
            'label' => $this->getOption('label'),
            'attr' => $attr,
            'multiple' => $this->getOption('multiple'),
            'class' => $this->getOption('class'),
            'trans_prefix' => $this->getOption('trans_prefix'),
            'trans_domain' => $this->getOption('trans_domain')
        );

        $builder->add('enum', 'enum', $options);
    }

    public function getDefaultOptions()
    {
        return array(
            'class' => null,
            'attr' => array(),
            'trans_prefix' => '',
            'trans_domain' => 'messages',
        );
    }

    public function getData()
    {
        return new FilterValue(array('enum' => $this->getOption('default')));
    }

    public function apply(\Closure $callback, $data)
    {
        if (!$data['enum']->is(Enum::__NULL)) {
            $callback($data['enum']);
        }
    }

    public function getAlias()
    {
        return 'enum';
    }

}
