<?php

namespace NetTeam\System\DataTableBundle\Filter\Type;

use Symfony\Component\Form\FormBuilder;
use NetTeam\System\DataTableBundle\Filter\Value\FilterValue;
use NetTeam\System\DataTableBundle\Filter\Type\FilterType;

/**
 * YearMonth type -- select fields field
 *
 * @author Wojciech Kulikowski <wojciech.kulikowski@netteam.pl>
 */
class YearMonthFilter extends FilterType
{


    protected $options = array();

    public function buildForm(FormBuilder $builder)
    {

        $builder->add('date', 'date', array(

            'required' => $this->getOption('required'),
            'label' => $this->getOption('label'),
            'format' => 'yyyy-MM',
            //'pattern' => '{{year}} - {{month}} - {{day}}'
        ));
    }

    public function getDefaultOptions()
    {
        return array(
            'required' => true,
            'label' => 'year_month'
        );
    }

    public function getData()
    {
        $default = $this->getOption('default');

        return new FilterValue($default);
    }

    public function apply(\Closure $callback, $data)
    {
        //echo $data['date']->format('Y-m-d');
        //die();
        if ($data['date'] instanceof \DateTime) {
            $from = new \DateTime($data['date']->format('Y-m-01'));
            $to = new \DateTime($data['date']->format('Y-m-t'));
            
            $callback($from, $to);
        }
    }

    public function getTemplate()
    {
        return 'NetTeamDataTableBundle:Filter:year_month.html.twig';
    }

}