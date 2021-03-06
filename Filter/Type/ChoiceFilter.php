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
    /**
     *
     * {@inheritdoc}
     */
    public function buildForm(FormBuilder $builder)
    {
        $this->checkType();

        $options = array(
            'required' => $this->getOption('required'),
            'label' => $this->getOption('label'),
            'attr' => $this->getOption('attr'),
            'empty_value' => $this->getOption('empty_value'),
                        'multiple' => $this->getOption('multiple'),
            'expanded' => $this->getOption('expanded'),
        );

        $type = $this->getOption('type');
        $attr = array_merge($this->getOption('attr'), array('data-filter-default' => $this->getOption('default')));

        if ($type == 'choice') {
            $options['choices'] = $this->getOption('choices');
        } else {
            $choices = $this->getOption('choices');
            $options['class'] = $this->getOption('class');
            $options['query_builder'] = $this->getOption('query_builder');

            if (count($choices) > 0) {
                $options['choices'] = $choices;
            }
        }

        $builder->add('choice', $type, $options);
    }
    /**
     *
     * {@inheritdoc}
     */
    public function getDefaultOptions()
    {
        return array(
            'attr' => array(),
            'choices' => array(),
            'type' => 'choice',
        );
    }
    /**
     * Checks type of form field
     * @throws UnexpectedTypeException Throws exception if $option['type'] isn't one of 'choice', 'entity' or 'document'
     */
    private function checkType()
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
    /**
     *
     * {@inheritdoc}
     */
    public function getData()
    {
        return new FilterValue(array('choice' => $this->getOption('default')));
    }
    /**
     *
     * {@inheritdoc}
     */
    public function apply(\Closure $callback, $data)
    {
        if ($data['choice']) {
            $callback($data['choice']);
        }
    }
    /**
     * {@inheritdoc}
     */
    public function getAlias()
    {
        return 'choice';
    }

}
