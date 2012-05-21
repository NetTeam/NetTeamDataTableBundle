<?php

namespace NetTeam\Bundle\DataTableBundle\Column\ValueGetter;

/**
 * Pobieranie wartości z pomocą domknięcia
 *
 * @author Krzysztof Menżyk <krzysztof.menzyk@carrywater.pl>
 */
class ClosureValueGetter implements ValueGetterInterface
{
    private $closure;

    public function __construct(\Closure $closure)
    {
        $this->closure = $closure;
    }

    public function get($objectOrArray)
    {
        $closure = $this->closure;

        return $closure($objectOrArray);
    }
}
