<?php

namespace NetTeam\System\DataTableBundle\Source;

/**
 * Puste źródło danych
 *
 * @author Krzysztof Menżyk <krzysztof.menzyk@carrywater.pl>
 */
class NullSource implements SourceInterface
{

    public function getData($offset, $limit)
    {
        return array();
    }

    public function getDataAll()
    {
        return array();
    }
    
    public function globalSearch(array $keys, $search)
    {
    }

    public function addSorting($column, $order)
    {
    }

    public function count()
    {
        return 0;
    }
}