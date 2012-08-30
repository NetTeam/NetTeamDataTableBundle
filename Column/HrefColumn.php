<?php

namespace NetTeam\Bundle\DataTableBundle\Column;

/**
 * Kolumna do wyświetlania linków
 *
 * @author Krzysztof Menżyk <krzysztof.menzyk@carrywater.pl>
 */
class HrefColumn extends ColumnDecorator
{
    public function __construct(ColumnInterface $column)
    {
        parent::__construct($column);

        $this->addClass("to-right no-wrap");
    }
}
