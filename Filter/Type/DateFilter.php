<?php

namespace NetTeam\Bundle\DataTableBundle\Filter\Type;

use Symfony\Component\Form\FormBuilder;
use NetTeam\Bundle\DataTableBundle\Filter\Value\FilterValue;
use NetTeam\Bundle\DataTableBundle\Filter\Type\FilterType;

/**
 * Date type -- input field
 *
 * @author Wojciech Muła <wojciech.mula@netteam.pl>
 */
class DateFilter extends FilterType
{

    protected $options = array();

    public function buildForm(FormBuilder $builder)
    {
        $label = $this->getOption('label');
        $builder->add('date', 'datepicker', array(
            'attr' => array('size' => 5),
            'required' => $this->getOption('required'),
            'max_length' => '10',
            'label' => $label,
        ));
    }

    public function getDefaultOptions()
    {
        return array(
            'required' => true,
            'label' => 'date',
        );
    }

    public function getData()
    {
        $default = $this->getOption('default');
        if (!($default instanceof \DateTime) && null !== $default) {
            throw new \Exception("DateFilter default values must be an instance of \DateTime");
        }

        return new FilterValue(array('date' => $default));
    }

    public function apply(\Closure $callback, $data)
    {
        if ($data['date']) {
            $callback($data['date']);
        }
    }

    public function getAlias()
    {
        return 'date';
    }

}
