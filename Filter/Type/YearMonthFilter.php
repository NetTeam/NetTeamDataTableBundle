<?php

namespace NetTeam\Bundle\DataTableBundle\Filter\Type;

use Symfony\Component\Form\FormBuilder;
use NetTeam\Bundle\DataTableBundle\Filter\Value\FilterValue;
use NetTeam\Bundle\DataTableBundle\Filter\Type\FilterType;

/**
 * YearMonth type -- select fields field
 *
 * @author Wojciech Kulikowski <wojciech.kulikowski@netteam.pl>
 */
class YearMonthFilter extends FilterType
{

    protected $options = array();
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilder $builder)
    {
        $builder->add('date', 'date', array(
            'required' => $this->getOption('required'),
            'label' => $this->getOption('label'),
            'attr' => array('data-filter-default' => $this->getOption('default')),
        ));
    }
    /**
     * {@inheritdoc}
     */
    public function getDefaultOptions()
    {
        return array(
            'required' => true,
            'label' => 'year_month'
        );
    }
    /**
     * {@inheritdoc}
     */
    public function getData()
    {
        $default = $this->getOption('default');

        return new FilterValue($default);
    }
    /**
     * {@inheritdoc}
     */
    public function apply(\Closure $callback, $data)
    {
        if ($data['date'] instanceof \DateTime) {
            $from = new \DateTime($data['date']->format('Y-m-01'));
            $to = new \DateTime($data['date']->format('Y-m-t'));

            $callback($from, $to);
        }
    }
    /**
     * {@inheritdoc}
     */
    public function getAlias()
    {
        return 'year_month';
    }

}
