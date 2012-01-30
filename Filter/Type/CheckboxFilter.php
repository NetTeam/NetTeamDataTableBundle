<?php

namespace NetTeam\System\DataTableBundle\Filter\Type;

use Symfony\Component\Form\FormBuilder;
use NetTeam\System\DataTableBundle\Filter\Value\FilterValue;
use NetTeam\System\DataTableBundle\Filter\Type\FilterType;

/**
 * Checkbox
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
        ));
    }

    public function getData()
    {
        return new FilterValue();
    }

    public function apply(\Closure $callback, $data)
    {
        if ($data['check']) {
            $callback();
        }
    }

    public function getTemplate()
    {
        return 'NetTeamDataTableBundle:Filter:checkbox.html.twig';
    }

}