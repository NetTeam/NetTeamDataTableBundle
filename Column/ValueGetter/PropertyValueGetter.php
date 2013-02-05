<?php

namespace NetTeam\Bundle\DataTableBundle\Column\ValueGetter;

use Symfony\Component\PropertyAccess\PropertyPath;
use Symfony\Component\PropertyAccess\PropertyAccessor;

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
        $propertyAccessor = new PropertyAccessor();

        return $propertyAccessor->getValue($objectOrArray, $this->propertyPath);
    }

}
