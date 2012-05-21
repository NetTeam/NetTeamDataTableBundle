<?php

namespace NetTeam\Bundle\DataTableBundle\Column\ValueGetter;

/**
 * Nie pobiera wartości z obiektu tylko zwraca tę przekazaną w konstruktorze
 *
 * @author Krzysztof Menżyk <krzysztof.menzyk@carrywater.pl>
 */
class AsIsValueGetter implements ValueGetterInterface
{
    private $key;

    public function __construct($key)
    {
        $this->key = $key;
    }

    public function get($objectOrArray)
    {
        return $this->key;
    }
}
