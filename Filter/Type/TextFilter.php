<?php

namespace NetTeam\Bundle\DataTableBundle\Filter\Type;

use Symfony\Component\Form\FormBuilder;
use NetTeam\Bundle\DataTableBundle\Filter\Value\FilterValue;
use NetTeam\Bundle\DataTableBundle\Filter\Type\FilterType;

/**
 * Default type -- input field
 *
 * @author Krzysztof Menżyk <krzysztof.menzyk@netteam.pl>
 */
class TextFilter extends FilterType
{

    protected $options = array();
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilder $builder)
    {
        $attr =  array_merge($this->getOption('attr'), array('data-filter-default' => $this->getOption('default')));

        $builder->add('value', 'text', array(
            'attr' => $attr,
            'label' => $this->getOption('label'),
            'required' => $this->getOption('required'),
        ));
    }
    /**
     * {@inheritdoc}
     */
    public function getDefaultOptions()
    {
        return array(
            'required' => false,
            'attr' => array(
                'size' => 7,
            ),
        );
    }
    /**
     * {@inheritdoc}
     */
    public function getData()
    {
        return new FilterValue(array('value' => $this->getOption('default')));
    }
    /**
     * {@inheritdoc}
     */
    public function apply(\Closure $callback, $data)
    {
        if ($data['value']) {
            $callback($data['value']);
        }
    }
    /**
     * {@inheritdoc}
     */
    public function getAlias()
    {
        return 'text';
    }

}
