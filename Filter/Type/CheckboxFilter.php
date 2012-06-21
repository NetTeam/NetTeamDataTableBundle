<?php

namespace NetTeam\Bundle\DataTableBundle\Filter\Type;

use Symfony\Component\Form\FormBuilder;
use NetTeam\Bundle\DataTableBundle\Filter\Value\FilterValue;
use NetTeam\Bundle\DataTableBundle\Filter\Type\FilterType;

/**
 * Checkbox filter
 *
 * @author Krzysztof Menżyk <krzysztof.menzyk@netteam.pl>
 */
class CheckboxFilter extends FilterType
{

    public function buildForm(FormBuilder $builder)
    {
        $builder->add('check', 'checkbox', array(
            'required' => $this->getOption('required'),
            'label' => $this->getOption('label'),
            'attr' => $this->getOption('attr'),
        ));
    }

    public function getDefaultOptions()
    {
        return array('attr' => array());
    }

    public function getData()
    {
        return new FilterValue(array('check' => $this->getOption('default')));
    }

    public function apply(\Closure $callback, $data)
    {
        if ($data['check']) {
            $callback();
        }
    }

    public function getAlias()
    {
        return 'checkbox';
    }

}
