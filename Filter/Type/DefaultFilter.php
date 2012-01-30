<?php

namespace NetTeam\System\DataTableBundle\Filter\Type;

use Symfony\Component\Form\FormBuilder;
use NetTeam\System\DataTableBundle\Filter\Value\FilterValue;
use NetTeam\System\DataTableBundle\Filter\Type\FilterType;

/**
 * Default type -- input field
 *
 * @author Krzysztof Menżyk <krzysztof.menzyk@netteam.pl>
 */
class DefaultFilter extends FilterType
{
    protected $options = array();

    public function buildForm(FormBuilder $builder)
    {
        $builder->add('value', 'text', array(
            'attr' => array('size' => 5),
            'required' => $this->getOption('required'),
            'label' => $this->getOption('label'),
        ));
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