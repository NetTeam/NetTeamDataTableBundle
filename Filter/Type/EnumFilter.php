<?php

namespace NetTeam\Bundle\DataTableBundle\Filter\Type;

use Symfony\Component\Form\FormBuilder;
use NetTeam\Bundle\DataTableBundle\Filter\Value\FilterValue;
use NetTeam\Bundle\DataTableBundle\Filter\Type\FilterType;

/**
 * EnumFilter
 *
 * @author Dawid Drelichowski <dawid.drelichowski@netteam.pl>
 */
class EnumFilter extends FilterType
{

    public function buildForm(FormBuilder $builder)
    {

        $options = array(
            'required' => $this->getOption('required'),
            'label' => $this->getOption('label'),
            'attr' => $this->getOption('attr'),
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
            'trans_prefix' => '',
            'trans_domain' => 'messages',
        );
    }

    public function getData()
    {
        return new FilterValue();
    }

    public function apply(\Closure $callback, $data)
    {
        if ($data['enum']) {
            $callback($data['enum']);
        }
    }

    public function getAlias()
    {
        return 'enum';
    }

}
