<?php

namespace NetTeam\System\DataTableBundle\Filter\Type;

use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use NetTeam\System\DataTableBundle\Filter\Value\FilterValue;
use NetTeam\System\DataTableBundle\Filter\Type\FilterType;

/**
 * ChoiceFilter
 *
 * @author zuo
 */
class ChoiceFilter extends FilterType
{

    public function buildForm(FormBuilder $builder)
    {
        $this->checkRequiredOptions();

        $type = $this->getOption('type');

        $builder->add('choice', $type, array(
            'required' => $this->getOption('required'),
            'label' => $this->getOption('label'),
            'attr' => $this->getOption('attr'),
            'choices' => $this->getOption('choices'),
            'class' => $this->getOption('class'),
            'query_builder' => $this->getOption('query_builder'),
        ));
    }

    public function getDefaultOptions()
    {
        return array(
            'attr' => array(),
            'choices' => array(),
            'type' => 'choice',
        );
    }

    private function checkRequiredOptions()
    {
        $availableTypes = array('choice', 'entity', 'document');
        $type = $this->getOption('type');

        if (!is_string($type)) {
            throw new UnexpectedTypeException($type, 'string');
        }

        if (!in_array($type, $availableTypes)) {
            throw new UnexpectedTypeException($type, 'choice, entity, document');
        }
        if ($type == 'entity' || $type == 'document') {
            $class = $this->getOption('class');
            if ($class == null) {
                throw new UnexpectedTypeException($class, 'document or entity class');
            }
        }
    }

    public function getData()
    {
        return new FilterValue();
    }

    public function apply(\Closure $callback, $data)
    {
        if ($data['choice']) {
            $callback($data['choice']);
        }
    }

    public function getTemplate()
    {
        return 'NetTeamDataTableBundle:Filter:choice.html.twig';
    }

}