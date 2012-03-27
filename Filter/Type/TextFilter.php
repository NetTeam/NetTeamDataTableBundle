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

    public function buildForm(FormBuilder $builder)
    {
        $builder->add('value', 'text', array(
            'attr' => $this->getOption('attr'),
            'label' => $this->getOption('label'),
            'required' => $this->getOption('required'),
        ));
    }

    public function getDefaultOptions()
    {
        return array(
            'required' => false,
            'attr' => array(
                'size' => 7,
            ),
        );
    }

    public function getData()
    {
        return new FilterValue();
    }

    public function apply(\Closure $callback, $data)
    {
        if ($data['value']) {
            $callback($data['value']);
        }
    }

    public function getTemplate()
    {
        return 'NetTeamDataTableBundle:Filter:default.html.twig';
    }

}
