<?php

namespace NetTeam\Bundle\DataTableBundle\Filter\Type;

use Symfony\Component\Form\FormBuilder;
use NetTeam\Bundle\DataTableBundle\Filter\Value\FilterValue;
use NetTeam\Bundle\DataTableBundle\Filter\Type\FilterType;

/**
 * DateRange type -- input field
 *
 * @author Wojciech Kulikowski <wojciech.kulikowski@netteam.pl>
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

    public function getDefaultOptions()
    {
        return array(
            'required' => true,
            'label' => array(
                'from' => 'date_from',
                'to' => 'date_to',
            )
        );
    }

    public function getData()
    {
        $default = $this->getOption('default');
        if (!isset($default['from'], $default['to'])) {
            throw new \Exception("DateRangeFilter default must be an array with keys 'from' and 'to'");
        }

        if (!($default['from'] instanceof \DateTime) || !($default['to'] instanceof \DateTime)) {
            throw new \Exception("DateRangeFilter default values must be an instances of \DateTime");
        }

        return new FilterValue($default);
    }

    public function apply(\Closure $callback, $data)
    {
        if ($data['from'] || $data['to']) {
            $callback($data['from'], $data['to']);
        }
    }

    public function getAlias()
    {
        return 'date_range';
    }

}
