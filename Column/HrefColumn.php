<?php

namespace NetTeam\System\DataTableBundle\Column;

use NetTeam\System\DataTableBundle\Column\ValueGetter\PropertyValueGetter;
use NetTeam\System\DataTableBundle\Column\ValueGetter\ClosureValueGetter;

/**
 * Kolumna do wyświetlania linków
 *
 * @author Krzysztof Menżyk <krzysztof.menzyk@carrywater.pl>
 */
class HrefColumn extends ColumnDecorator
{
    private $route;

    private $params = array();

    private $class;

    public function getValue($objectOrArray)
    {
        return array(
            'column' => $this->column,
            'value'  => $this->column->getValue($objectOrArray),
            'class'  => $this->class,
            'route'  => $this->route,
            'params' => $this->parseParams($objectOrArray)
        );
    }

    public function getTemplate()
    {
        return 'href_column';
    }

    public function setRoute($route, array $params, $class = null)
    {
        $this->route = $route;
        $this->class = $class;

        foreach ($params as $key => $getterKey) {
            $this->params[$key] = $this->createValueGetter($getterKey);
        }

        return $this;
    }

    private function createValueGetter($getterKey)
    {
        if (is_string($getterKey)) {
            return new PropertyValueGetter($getterKey);
        }

        if ($getterKey instanceof \Closure) {
            return new ClosureValueGetter($getterKey);
        }

        throw new \InvalidArgumentException('Niepoprawna definicja klucza dla ścieżki');
    }

    public function route($route, array $params, $class = null)
    {
        return $this->setRoute($route, $params, $class);
    }

    private function parseParams($objectOrArray)
    {
        $params = array();
        foreach ($this->params as $key => $path) {
            $params[$key] = $path->get($objectOrArray);
        }

        return $params;
    }
}
