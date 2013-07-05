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
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilder $builder)
    {
        $attr =  array_merge($this->getOption('attr'), array('data-filter-default', $this->getOption('default')));
        $builder->add('check', 'checkbox', array(
            'required' => $this->getOption('required'),
            'label' => $this->getOption('label'),
            'attr' => $attr,
        ));
    }
    /**
     * {@inheritdoc}
     */
    public function getDefaultOptions()
    {
        return array('attr' => array());
    }
    /**
     * {@inheritdoc}
     */
    public function getData()
    {
        return new FilterValue(array('check' => $this->getOption('default')));
    }
    /**
     * {@inheritdoc}
     */
    public function apply(\Closure $callback, $data)
    {
        if ($data['check']) {
            $callback();
        }
    }
    /**
     * {@inheritdoc}
     */
    public function getAlias()
    {
        return 'checkbox';
    }

}
