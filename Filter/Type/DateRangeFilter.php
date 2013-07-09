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
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilder $builder)
    {
        $label = $this->getOption('label');
        $default = $this->getOption('default');

        $builder->add('from', 'datepicker', array(
            'attr' => array('size' => 5, 'data-filter-default' => $default['from']),
            'required' => $this->getOption('required'),
            'max_length' => '10',
            'label' => $label['from'],
        ));

        $builder->add('to', 'datepicker', array(
            'attr' => array('size' => 5, 'data-filter-default' => $default['to']),
            'required' => $this->getOption('required'),
            'max_length' => '10',
            'label' => $label['to'],
        ));
    }
    /**
     * {@inheritdoc}
     */
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
    /**
     * {@inheritdoc}
     */
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
    /**
     * {@inheritdoc}
     */
    public function apply(\Closure $callback, $data)
    {
        if ($data['from'] || $data['to']) {
            $callback($data['from'], $data['to']);
        }
    }
    /**
     * {@inheritdoc}
     */
    public function getAlias()
    {
        return 'date_range';
    }

}
