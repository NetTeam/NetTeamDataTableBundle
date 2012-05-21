<?php
namespace NetTeam\Bundle\DataTableBundle\SimpleSource;

/**
 * Źródło danych dla SimpleDataTable - tablica
 *
 * @author Krzysztof Menżyk <krzysztof.menzyk@carrywater.pl>
 */
class ArraySource implements SimpleSourceInterface
{
    protected $array;

    public function __construct(array $array)
    {
        $this->array = $array;
    }

    public function getData()
    {
        return $this->array;
    }
}
