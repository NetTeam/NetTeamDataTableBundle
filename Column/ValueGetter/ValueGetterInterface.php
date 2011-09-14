<?php

namespace NetTeam\System\DataTableBundle\Column\ValueGetter;

/**
 * Interfejs dla klas pobierających wartości dla kolumny
 *
 * @author Krzysztof Menżyk <krzysztof.menzyk@carrywater.pl>
 */
interface ValueGetterInterface
{
    public function get($objectOrArray);
}