<?php

namespace NetTeam\Bundle\DataTableBundle\Form\Extension;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormBuilderInterface;

class DateTypeExtension extends AbstractTypeExtension
{
    /**
     * {@inheritdoc}
     */
    public function getExtendedType()
    {
        return 'date';
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        if ($builder->hasAttribute('attr') && $builder->hasAttribute('formatter')) {
            $attr = $builder->getAttribute('attr');
            if (isset($attr['data-filter-default'])) {
                $attr['data-filter-default'] = $builder->getAttribute('formatter')->format($attr['data-filter-default']);
                $builder->setAttribute('attr', $attr);
            }
        }
    }
}
