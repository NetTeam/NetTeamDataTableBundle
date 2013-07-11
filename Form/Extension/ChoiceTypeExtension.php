<?php

namespace NetTeam\Bundle\DataTableBundle\Form\Extension;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormBuilderInterface;

class ChoiceTypeExtension extends AbstractTypeExtension
{
    /**
     * {@inheritdoc}
     */
    public function getExtendedType()
    {
        return 'choice';
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        if ($builder->hasAttribute('attr')) {
            $attr = $builder->getAttribute('attr');
            if (isset($attr['data-filter-default']) && is_array($attr['data-filter-default'])) {
                $attr['data-filter-default'] = json_encode($attr['data-filter-default']);
                $builder->setAttribute('attr', $attr);
            }
        }
    }
}
