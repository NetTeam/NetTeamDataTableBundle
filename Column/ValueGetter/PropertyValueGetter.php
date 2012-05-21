<?php

namespace NetTeam\Bundle\DataTableBundle\Column\ValueGetter;

use Symfony\Component\Form\Util\PropertyPath;

/**
 * Pobieranie wartości z wykorzystaniem PropertyPath
 *
 * @author Krzysztof Menżyk <krzysztof.menzyk@carrywater.pl>
 */
class PropertyValueGetter implements ValueGetterInterface
{
    private $propertyPath;

    public function __construct($key)
    {
        $this->propertyPath = new PropertyPath($key);
    }

    public function get($objectOrArray)
    {
        return $this->propertyPath->getValue($objectOrArray);
    }
}
