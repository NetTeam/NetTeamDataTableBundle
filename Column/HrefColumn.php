<?php

namespace NetTeam\Bundle\DataTableBundle\Column;

/**
 * Kolumna do wyświetlania linków
 *
 * @author Krzysztof Menżyk <krzysztof.menzyk@carrywater.pl>
 */
class HrefColumn extends ColumnDecorator
{

    public function __construct($name, $getters, array $parameters)
    {
        parent::__construct($name, $getters, $parameters);
        $this->column->addClass('to-right no-wrap');
    }

    public static function create($name, $getters, array $parameters)
    {
        $column = parent::create($name, $getters, $parameters);
        $column->addClass('to-right no-wrap');

        return $column;
    }

    protected function getColumn()
    {
        return 'NetTeam\Bundle\DataTableBundle\Column\TextColumn';
    }

}
