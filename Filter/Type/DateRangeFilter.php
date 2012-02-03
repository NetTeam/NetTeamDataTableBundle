<?php

namespace NetTeam\System\DataTableBundle\Filter\Type;

use Symfony\Component\Form\FormBuilder;
use NetTeam\System\DataTableBundle\Filter\Value\FilterValue;
use NetTeam\System\DataTableBundle\Filter\Type\FilterType;

/**
 * DateRange type -- input field
 *
 * @author Krzysztof Menżyk <krzysztof.menzyk@netteam.pl>
 */
class DateRangeFilter extends FilterType
{


    protected $options = array();

    public function buildForm(FormBuilder $builder)
    {
        $label = $this->getOption('label');
        if (!isset($label['from'], $label['to'])) {
            throw new \Exception("DateRangeFilter label must be an array with keys 'from' and 'to'.");
        }

        $builder->add('from', 'datepicker', array(
            'attr' => array('size' => 5),
            'required' => $this->getOption('required'),
            'max_length' => '10',
            'label' => $label['from'],
        ));

        $builder->add('to', 'datepicker', array(
            'attr' => array('size' => 5),
            'required' => $this->getOption('required'),
            'max_length' => '10',
            'label' => $label['to'],
        ));
    }

    public function getData()
    {
        return new FilterValue();
    }

    public function apply(\Closure $callback, $data)
    {
        if ($data['from'] && $data['to']) {
            $callback($data['from'], $data['to']);
        }
    }

    public function getTemplate()
    {
        return 'NetTeamDataTableBundle:Filter:date_range.html.twig';
    }

}