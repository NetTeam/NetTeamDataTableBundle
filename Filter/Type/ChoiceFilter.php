<?php

namespace NetTeam\Bundle\DataTableBundle\Filter\Type;

use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use NetTeam\Bundle\DataTableBundle\Filter\Value\FilterValue;
use NetTeam\Bundle\DataTableBundle\Filter\Type\FilterType;

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

        if ($type == 'choice') {
            $options = array(
                'required' => $this->getOption('required'),
                'label' => $this->getOption('label'),
                'attr' => $this->getOption('attr'),
                'choices' => $this->getOption('choices'),
                'empty_value' => $this->getOption('empty_value'),
            );
        } else {
            $options = array(
                'required' => $this->getOption('required'),
                'label' => $this->getOption('label'),
                'attr' => $this->getOption('attr'),
                'class' => $this->getOption('class'),
                'query_builder' => $this->getOption('query_builder'),
                'empty_value' => $this->getOption('empty_value'),
            );

            $choices = $this->getOption('choices');
            if (count($choices) > 0) {
                $options['choices'] = $choices;
            }
        }

        $builder->add('choice', $type, $options);
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

    public function getAlias()
    {
        return 'choice';
    }

}
