<?php

namespace NetTeam\Bundle\DataTableBundle\Column;

/**
 * BoolColumn
 *
 * Trzeci parametr przyjmuje wartości do tłumaczenia:
 *   text_true - domyślnie 'tak'
 *   text_false - domyślnie 'nie'
 *
 * @author Wojciech Kulikowski <wojciech.kulikowski@carryater.pl>
 */
class BoolColumn extends Column
{
    protected $template = 'bool_column';

    /**
     * {@inheritdoc}
     */
    public function __construct($caption, $getters, $parameters = array())
    {
        $defaultParameters = array(
            'text_true' => 'column.bool.textTrue',
            'text_false' => 'column.bool.textFalse',
        );

        parent::__construct($caption, $getters, array_merge($defaultParameters, $parameters));
    }
}
